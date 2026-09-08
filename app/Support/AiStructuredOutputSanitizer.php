<?php

namespace App\Support;

/**
 * Cleans AI structured-output payloads that leaked reasoning artifacts,
 * markdown syntax, or report scaffolding into otherwise well-shaped data.
 *
 * Some OpenAI-compatible providers ignore `response_format: json_object`
 * and answer with a markdown report (often wrapped in `<details>` reasoning
 * blocks). When the gateway falls back to plaintext parsing, artifacts such
 * as bare `**` separators, `**ATS Score: 82/100**` headers, `**Step 7: ...**`
 * reasoning steps, and `<details>/<summary>` tags can end up inside the
 * structured fields. This sanitizer removes that noise.
 */
class AiStructuredOutputSanitizer
{
    /**
     * Patterns that mark an item as reasoning noise rather than genuine
     * content. Applied to the item AFTER inline markdown has been stripped.
     *
     * @var array<int, string>
     */
    protected const JUNK_PATTERNS = [
        '/^\*+$/',                                            // bare "**" separators
        '/^[-=_~]{3,}$/',                                     // horizontal rules
        '/^<\/?(details|summary)(\s[^>]*)?>?$/i',             // open/close detail tags
        '/<\/?(details|summary)(\s[^>]*)?>/i',                // residual detail/summary tags
        '/^\s*step\s+\d+\s*[:.]/i',                           // "**Step 7: Final review...**"
        '/^(ats|format|about me|impact|role alignment|security|reliability|cloud|job match)\s+score\b.*\d/i',
        '/^keywords\.(found|missing|weak_?evidence)\s*:?$/i', // "**keywords.weak_evidence:**"
        '/[✓✔]\s*$/u',                                        // rubric confirmation lines
        '/^\W*reasoning\s+process/iu',                        // ">🔍 Reasoning Process"
        '/^(?:[^,\n]*\s*,){9,}/',                             // keyword dumps (10+ comma-separated tokens)
        '/^"[^"]{0,160}"\s*[—–-]\s*/u',                       // quoted weak-evidence claims ("X" — reason)
    ];

    /**
     * Sanitize an entire structured-output payload in place: strings are
     * cleaned via cleanText(), list items are filtered via cleanList(),
     * and associative sub-objects are recursed into.
     */
    public static function sanitize(array $payload): array
    {
        foreach ($payload as $key => $value) {
            if ($key === 'experience_review') {
                $payload[$key] = self::normalizeReviewList($value);

                continue;
            }

            $payload[$key] = is_array($value)
                ? self::sanitizeValue($value)
                : (is_string($value) ? self::cleanText($value) : $value);
        }

        return $payload;
    }

    /**
     * Normalize an experience_review payload into the schema shape — a list
     * of objects with section/strength/gap/rewrite_hint keys — regardless of
     * what the provider produced:
     *
     *  - a markdown table (header row, separator row, `|`-delimited rows)
     *  - an associative map like {senior_role: "comment", ...}
     *  - a list of plain strings (rendered as review notes)
     *  - a list of already-correct objects
     */
    public static function normalizeReviewList(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        // Associative shape: {role_key: comment, ...} -> one entry per key.
        if (self::isAssociative($items)) {
            $normalized = [];

            foreach ($items as $key => $value) {
                $comment = is_string($value) ? self::cleanText($value) : '';

                if ($comment === '') {
                    continue;
                }

                $normalized[] = [
                    'section' => self::humanizeKey((string) $key),
                    'strength' => '',
                    'gap' => '',
                    'rewrite_hint' => $comment,
                ];
            }

            return $normalized;
        }

        $normalized = [];
        $headers = null;

        foreach ($items as $item) {
            if (is_array($item)) {
                if (self::isAssociative($item)) {
                    $row = [];

                    foreach (['section', 'strength', 'gap', 'rewrite_hint'] as $key) {
                        $row[$key] = self::cleanText((string) ($item[$key] ?? ''));
                    }

                    if (trim(implode('', $row)) !== '') {
                        $normalized[] = $row;
                    }
                }

                continue;
            }

            if (! is_string($item)) {
                continue;
            }

            $line = self::cleanText($item);

            if ($line === '' || self::isJunk($line)) {
                continue;
            }

            // Markdown table row: | a | b | c | d |
            if (str_starts_with($line, '|')) {
                $cells = array_map('trim', explode('|', trim($line, '|')));

                if ($cells === [] || $cells === ['']) {
                    continue;
                }

                // Separator row like |---|---|
                if (preg_match('/^[-:]+$/', str_replace(' ', '', $cells[0]))) {
                    continue;
                }

                if ($headers === null) {
                    $candidates = array_map(
                        fn ($cell) => str_replace(' ', '_', strtolower($cell)),
                        $cells
                    );

                    if (count(array_intersect($candidates, ['section', 'strength', 'gap', 'rewrite_hint'])) > 0) {
                        $headers = $candidates;

                        continue;
                    }

                    // No recognizable header row: fall back to positional mapping.
                    $headers = ['section', 'strength', 'gap', 'rewrite_hint'];
                }

                $row = [];

                foreach ($headers as $index => $header) {
                    $key = in_array($header, ['section', 'strength', 'gap', 'rewrite_hint'], true)
                        ? $header
                        : 'section';
                    $row[$key] = self::cleanText($cells[$index] ?? '');
                }

                foreach (['section', 'strength', 'gap', 'rewrite_hint'] as $key) {
                    $row[$key] = self::cleanText($row[$key] ?? '');
                }

                if (trim(implode('', $row)) !== '') {
                    $normalized[] = $row;
                }

                continue;
            }

            // Plain string line: keep it as a single review note.
            $normalized[] = [
                'section' => '',
                'strength' => '',
                'gap' => '',
                'rewrite_hint' => $line,
            ];
        }

        return $normalized;
    }

    /**
     * Remove `<details>` reasoning blocks (and any dangling `<details>`
     * section) from raw AI content before JSON extraction is attempted.
     */
    public static function stripReasoningBlocks(string $content): string
    {
        // Remove complete <details>...</details> blocks first.
        $content = preg_replace('/<details[^>]*>.*?<\/details>\s*/is', '', $content) ?? $content;

        // If a <details> block was never closed, drop everything from its
        // opening tag onward — nothing trustworthy follows it.
        if (preg_match('/<details[^>]*>/i', $content) && ! str_contains($content, '</details>')) {
            $content = preg_replace('/<details[^>]*>.*$/is', '', $content) ?? $content;
        }

        return trim($content);
    }

    /**
     * Clean a free-text field (e.g. summary): strip detail/summary tags,
     * bold/italic markers, and inline code ticks; collapse extra newlines.
     */
    public static function cleanText(string $text): string
    {
        $text = preg_replace('/<details[^>]*>/i', '', $text) ?? $text;
        $text = preg_replace('/<\/details>/i', '', $text) ?? $text;
        $text = preg_replace('/<summary[^>]*>.*?<\/summary>/is', '', $text) ?? $text;
        $text = preg_replace('/<\/?summary[^>]*>/i', '', $text) ?? $text;
        $text = str_replace(['**', '__', '`'], '', $text);

        // Drop lines that are reasoning artifacts, keep genuine prose.
        $lines = preg_split('/\R/', $text) ?: [];
        $kept = [];

        foreach ($lines as $line) {
            $stripped = self::stripInlineMarkdown($line);

            if ($stripped === '') {
                $kept[] = '';

                continue;
            }

            if (self::isJunk($stripped)) {
                continue;
            }

            $kept[] = $stripped;
        }

        $text = implode("\n", $kept);
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }

    /**
     * Filter a list of strings down to genuine content items.
     *
     * @return array<int, string>
     */
    public static function cleanList(array $items): array
    {
        $cleaned = [];

        foreach ($items as $item) {
            if (is_array($item)) {
                $cleaned[] = self::sanitizeValue($item);

                continue;
            }

            if (! is_string($item)) {
                $cleaned[] = $item;

                continue;
            }

            $value = self::stripInlineMarkdown($item);

            if ($value === '' || self::isJunk($value)) {
                continue;
            }

            $cleaned[] = $value;
        }

        // array_unique() casts every element to a string for comparison, which
        // triggers "Array to string conversion" when the provider returned a
        // list of objects (e.g. [{...}, {...}]). Deduplicate only when every
        // element is a scalar; otherwise keep the cleaned items as-is.
        $hasArrayItems = array_filter($cleaned, 'is_array') !== [];

        return $hasArrayItems
            ? array_values($cleaned)
            : array_values(array_unique($cleaned));
    }

    protected static function sanitizeValue(mixed $value): mixed
    {
        if (is_string($value)) {
            return self::cleanText($value);
        }

        if (! is_array($value)) {
            return $value;
        }

        if (self::isAssociative($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = is_array($item)
                    ? self::sanitizeValue($item)
                    : (is_string($item) ? self::cleanText($item) : $item);
            }

            return $value;
        }

        return self::cleanList($value);
    }

    protected static function isJunk(string $item): bool
    {
        foreach (self::JUNK_PATTERNS as $pattern) {
            if (preg_match($pattern, $item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Strip list bullets, numbering, quote markers, bold/italic emphasis,
     * and inline code ticks from a single item.
     */
    protected static function stripInlineMarkdown(string $item): string
    {
        $item = trim($item);
        $item = preg_replace('/^\s*[-•*+]\s+/', '', $item) ?? $item;
        $item = preg_replace('/^\s*\d+[.)]\s+/', '', $item) ?? $item;
        $item = preg_replace('/^\s*>\s?/', '', $item) ?? $item;
        $item = str_replace(['**', '__', '`'], '', $item);

        return trim($item);
    }

    protected static function isAssociative(array $array): bool
    {
        return $array !== [] && array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Convert a snake_case key like "senior_role" into a readable section
     * title like "Senior Role".
     */
    protected static function humanizeKey(string $key): string
    {
        return ucwords(str_replace('_', ' ', trim($key)));
    }
}
