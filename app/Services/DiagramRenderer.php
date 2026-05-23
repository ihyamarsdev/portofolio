<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class DiagramRenderer
{
    protected string $mmdc;

    public function __construct()
    {
        $this->mmdc = base_path('node_modules/.bin/mmdc');
    }

    /**
     * Render Mermaid code to SVG
     *
     * @param  string  $mermaidCode  The Mermaid diagram code
     * @return string|null The SVG content or null on failure
     */
    public function renderToSvg(string $mermaidCode): ?string
    {
        if (empty(trim($mermaidCode))) {
            return null;
        }

        // Create temp files for input and output
        $inputFile = storage_path('app/temp/'.Str::uuid().'.mmd');
        $outputFile = storage_path('app/temp/'.Str::uuid().'.svg');

        try {
            // Ensure temp directory exists
            if (! is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // Write mermaid code to input file
            file_put_contents($inputFile, $mermaidCode);

            $nodePath = config('services.mermaid.node_path');
            $currentPath = getenv('PATH') ?: '/usr/local/bin:/usr/bin:/bin';
            $processPath = implode(':', array_filter([$nodePath, $currentPath]));

            // Run mmdc command with proper PATH
            $result = Process::env([
                'PATH' => $processPath,
            ])->run([
                $this->mmdc,
                '-i', $inputFile,
                '-o', $outputFile,
                '-b', 'transparent',
                '-t', 'default',
            ]);

            if ($result->failed()) {
                Log::error('Mermaid CLI Error: '.$result->errorOutput());

                return null;
            }

            // Read SVG content
            if (file_exists($outputFile)) {
                $svgContent = file_get_contents($outputFile);

                return $svgContent;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('DiagramRenderer Exception: '.$e->getMessage());

            return null;
        } finally {
            // Cleanup temp files
            if (file_exists($inputFile)) {
                unlink($inputFile);
            }
            if (file_exists($outputFile)) {
                unlink($outputFile);
            }
        }
    }

    /**
     * Render Mermaid code and save as a media file on a model
     *
     * @param  Model  $model  The model with HasMedia trait
     * @param  string  $mermaidCode  The Mermaid diagram code
     * @param  string  $collection  The media collection name
     * @return bool Whether the operation succeeded
     */
    public function renderAndSaveToMedia($model, string $mermaidCode, string $collection = 'diagrams'): bool
    {
        $svgContent = $this->renderToSvg($mermaidCode);

        if (! $svgContent) {
            return false;
        }

        try {
            // Create temp file with SVG content
            $tempPath = storage_path('app/temp/'.Str::uuid().'.svg');

            if (! is_dir(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            file_put_contents($tempPath, $svgContent);

            // Clear existing diagrams and add new one
            $model->clearMediaCollection($collection);
            $model->addMedia($tempPath)
                ->usingFileName('architecture-diagram.svg')
                ->toMediaCollection($collection);

            return true;

        } catch (\Exception $e) {
            Log::error('DiagramRenderer Save Exception: '.$e->getMessage());

            return false;
        }
    }
}
