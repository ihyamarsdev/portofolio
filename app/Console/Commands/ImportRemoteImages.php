<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImportRemoteImages extends Command
{
    protected const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/gif',
    ];

    protected const MAX_BYTES = 25 * 1024 * 1024; // 25 MB

    protected const TIMEOUT_SECONDS = 30;

    protected $signature = 'media:import-remote-images
        {--model=all : projects|posts|all}
        {--dry-run : Simulate the import without downloading or writing changes}
        {--limit= : Limit the number of records to process per model}';

    protected $description = 'Download remote project and post images into Spatie Media collections, generate WebP conversions, and clear remote URLs.';

    public function handle(): int
    {
        $modelOption = strtolower((string) $this->option('model'));
        $dryRun = (bool) $this->option('dry-run');
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;

        if (! in_array($modelOption, ['all', 'projects', 'posts'], true)) {
            $this->error("Invalid --model option: {$modelOption}. Allowed values are: all, projects, posts.");

            return self::FAILURE;
        }

        $targets = [];

        if (in_array($modelOption, ['all', 'projects'], true)) {
            $targets['projects'] = [
                'model' => Project::class,
                'url_column' => 'image_url',
                'collection' => 'images',
            ];
        }

        if (in_array($modelOption, ['all', 'posts'], true)) {
            $targets['posts'] = [
                'model' => Post::class,
                'url_column' => 'featured_image_url',
                'collection' => 'featured_image',
            ];
        }

        $allRows = [];
        $failedCount = 0;
        $successCount = 0;
        $skippedCount = 0;

        foreach ($targets as $label => $config) {
            $this->info("Scanning {$label} for remote images...");

            /** @var class-string<Model&HasMedia> $modelClass */
            $modelClass = $config['model'];
            $urlColumn = $config['url_column'];
            $collection = $config['collection'];

            $query = $modelClass::query()
                ->whereNotNull($urlColumn)
                ->where($urlColumn, '!=', '');

            if ($limit !== null && $limit > 0) {
                $query->limit($limit);
            }

            $records = $query->get();

            foreach ($records as $record) {
                $url = (string) $record->getAttribute($urlColumn);
                $title = $this->formatTitle($record);

                // Check if already has media in this collection
                if ($record->hasMedia($collection)) {
                    $skippedCount++;
                    $allRows[] = [
                        'id' => "{$label}#{$record->getKey()}",
                        'title' => $title,
                        'source' => $url,
                        'original_kb' => '-',
                        'webp_kb' => '-',
                        'status' => 'SKIPPED (has media)',
                    ];

                    continue;
                }

                if ($dryRun) {
                    $allRows[] = [
                        'id' => "{$label}#{$record->getKey()}",
                        'title' => $title,
                        'source' => $url,
                        'original_kb' => '-',
                        'webp_kb' => '-',
                        'status' => 'WOULD_IMPORT',
                    ];

                    continue;
                }

                $result = $this->importRecord($record, $urlColumn, $collection, $url, $title, $label);
                $allRows[] = $result['row'];

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }
        }

        if (empty($allRows)) {
            $this->info('No eligible records found.');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Title', 'Source URL', 'Original KB', 'WebP KB', 'Status'],
            $allRows
        );

        if ($dryRun) {
            $this->info('Dry-run complete. No changes were made.');

            return self::SUCCESS;
        }

        $this->info("Summary: {$successCount} imported, {$failedCount} failed, {$skippedCount} skipped.");

        return $failedCount > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return array{success: bool, row: array<string, string>}
     */
    protected function importRecord(
        Model&HasMedia $record,
        string $urlColumn,
        string $collection,
        string $url,
        string $title,
        string $label
    ): array {
        $tempFile = null;

        try {
            if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
                throw new Exception("URL must start with http:// or https://: {$url}");
            }

            // Download with Http client
            $response = Http::timeout(self::TIMEOUT_SECONDS)->get($url);

            if (! $response->successful()) {
                throw new Exception("HTTP request failed with status {$response->status()}");
            }

            $body = $response->body();
            $bodySize = strlen($body);

            if ($bodySize === 0) {
                throw new Exception('Downloaded empty response');
            }

            if ($bodySize > self::MAX_BYTES) {
                throw new Exception("File size ({$bodySize} bytes) exceeds 25MB limit");
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'media_import_');
            if ($tempFile === false) {
                throw new Exception('Failed to create temporary file');
            }

            file_put_contents($tempFile, $body);

            // Validate mime type
            $mimeType = mime_content_type($tempFile) ?: 'application/octet-stream';
            if (! in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
                throw new Exception("Disallowed mime type: {$mimeType}");
            }

            // Extract filename from URL
            $urlPath = parse_url($url, PHP_URL_PATH);
            $filename = $urlPath ? basename($urlPath) : '';
            $filename = urldecode($filename);
            if ($filename === '' || ! str_contains($filename, '.')) {
                $extension = match ($mimeType) {
                    'image/jpeg', 'image/jpg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    'image/gif' => 'gif',
                    default => 'bin',
                };
                $filename = ($filename !== '' ? $filename : 'image').'.'.$extension;
            }

            // Store media
            /** @var Media $media */
            $media = $record->addMedia($tempFile)
                ->usingFileName($filename)
                ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                ->toMediaCollection($collection);

            // Re-fetch media to check conversion
            $media->refresh();

            if (! $media->hasGeneratedConversion('webp')) {
                throw new Exception("WebP conversion was not generated for media #{$media->id}");
            }

            $originalKb = number_format($media->size / 1024, 2);

            $conversionsDisk = $media->conversions_disk ?: $media->disk;
            $webpRelativePath = $media->getPathRelativeToRoot('webp');
            $webpSize = Storage::disk($conversionsDisk)->size($webpRelativePath);
            $webpKb = number_format($webpSize / 1024, 2);

            // Clear remote URL column
            $record->setAttribute($urlColumn, null);
            $record->save();

            return [
                'success' => true,
                'row' => [
                    'id' => "{$label}#{$record->getKey()}",
                    'title' => $title,
                    'source' => $url,
                    'original_kb' => $originalKb,
                    'webp_kb' => $webpKb,
                    'status' => 'IMPORTED',
                ],
            ];
        } catch (Exception $e) {
            $this->warn("Failed importing {$label}#{$record->getKey()}: {$e->getMessage()}");

            return [
                'success' => false,
                'row' => [
                    'id' => "{$label}#{$record->getKey()}",
                    'title' => $title,
                    'source' => $url,
                    'original_kb' => '-',
                    'webp_kb' => '-',
                    'status' => 'FAILED: '.$e->getMessage(),
                ],
            ];
        } finally {
            if ($tempFile && file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
    }

    protected function formatTitle(Model $record): string
    {
        if (method_exists($record, 'getTranslation')) {
            $title = $record->getTranslation('title', 'en', false)
                ?: $record->getTranslation('title', 'id', false);

            if ($title) {
                return $title;
            }
        }

        $rawTitle = $record->getAttribute('title');
        if (is_string($rawTitle) && $rawTitle !== '') {
            return $rawTitle;
        }

        $slug = $record->getAttribute('slug');
        if (is_string($slug) && $slug !== '') {
            return $slug;
        }

        return (string) $record->getKey();
    }
}
