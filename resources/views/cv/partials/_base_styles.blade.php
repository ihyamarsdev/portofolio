{{-- Shared CV print/typography foundation.
     Included by all four CV templates inside <head><style>.
     Templates may override any CSS custom property in :root AFTER this include
     to change font stack, accent, muted tone, header alignment or divider style.

     Tokens (override per template):
       --cv-font, --cv-font-display, --cv-font-mono
       --cv-accent, --cv-accent-soft, --cv-text, --cv-text-soft, --cv-muted
       --cv-rule, --cv-sheet, --cv-page-bg
       --cv-body-size, --cv-body-leading, --cv-section-gap
       --cv-header-align, --cv-divider
--}}
@page {
    size: A4;
    margin: 13mm 14mm;
}

*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --cv-font: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --cv-font-display: var(--cv-font);
    --cv-font-mono: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    --cv-accent: #3b82f6;
    --cv-accent-soft: #dbeafe;
    --cv-text: #0f172a;
    --cv-text-soft: #334155;
    --cv-muted: #64748b;
    --cv-rule: #e2e8f0;
    --cv-sheet: #ffffff;
    --cv-page-bg: #f1f5f9;
    --cv-body-size: 10pt;
    --cv-body-leading: 1.4;
    --cv-section-gap: 20px;
    --cv-header-align: left;
    --cv-divider: 1px solid var(--cv-rule);
}

html {
    -webkit-text-size-adjust: 100%;
}

body {
    font-family: var(--cv-font);
    font-size: var(--cv-body-size);
    line-height: var(--cv-body-leading);
    color: var(--cv-text);
    background: var(--cv-sheet);
    -webkit-font-smoothing: antialiased;
}

/* Screen preview: a centered A4 sheet on a neutral desk. */
@media screen {
    body {
        background: var(--cv-page-bg);
        padding: 2rem 0 6rem 0;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        background: var(--cv-sheet);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
}

/* Print: the sheet becomes the page; no shadows, no forced height. */
@media print {
    @page {
        size: A4;
        margin: 13mm 14mm;
    }

    html, body {
        background: #ffffff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .page {
        width: auto !important;
        min-height: 0 !important;
        height: auto !important;
        margin: 0 !important;
        box-shadow: none !important;
    }

    /* Section headings never print at the bottom of a page alone. */
    .section-header,
    .section-title,
    .skills-header,
    h1, h2, h3, h4 {
        break-after: avoid;
        page-break-after: avoid;
    }

    /* Individual entries never split across pages. */
    .experience-item,
    .education-item,
    .project-item,
    .certificate-item,
    .skill-category,
    .item {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    ul, ol {
        break-inside: avoid;
        page-break-inside: auto;
    }

    li {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    a {
        color: inherit;
        text-decoration: none;
    }
}

/* Shared scan-layout primitives consumed by every template. */
.section {
    margin-bottom: var(--cv-section-gap);
}

.section:last-child {
    margin-bottom: 0;
}

.summary {
    color: var(--cv-text-soft);
}

/* Row 1: Role — Company (left) | Date range (right, tabular).
   Row 2: Location / context (muted). */
.entry-head {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 12px;
}

.entry-role {
    font-weight: 700;
    color: var(--cv-text);
}

.entry-company {
    color: var(--cv-text-soft);
}

.entry-date {
    font-variant-numeric: tabular-nums;
    font-feature-settings: 'tnum';
    white-space: nowrap;
    color: var(--cv-muted);
}

.entry-context {
    color: var(--cv-muted);
    font-size: 0.9em;
}

.bullets {
    margin-top: 6px;
    padding-left: 16px;
}

.bullets li {
    color: var(--cv-text-soft);
    margin-bottom: 3px;
    line-height: 1.4;
    font-size: 9.8pt;
}

.bullets li::marker {
    color: var(--cv-accent);
}

/* Grouped skills: "Category: Skill A, Skill B, Skill C". */
.skill-line {
    color: var(--cv-text-soft);
}

.skill-line-label {
    font-weight: 700;
    color: var(--cv-text);
}

.cv-note {
    font-size: 8.5pt;
    color: var(--cv-muted);
    font-style: italic;
    margin-top: 12px;
    border-top: 1px dashed var(--cv-rule);
    padding-top: 12px;
}

.cv-note a {
    color: var(--cv-accent);
    text-decoration: none;
}
