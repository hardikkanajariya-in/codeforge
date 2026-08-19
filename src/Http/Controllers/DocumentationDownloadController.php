<?php

namespace HkDevs\CodeForgeStudio\Http\Controllers;

use HkDevs\CodeForgeStudio\Models\DocumentationGeneration;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentationDownloadController
{
    public function download($generationId): Response|StreamedResponse
    {
        // Debug logging
        Log::info('Download requested for generation ID: '.$generationId);

        $generation = DocumentationGeneration::findOrFail($generationId);

        Log::info('Generation found: '.$generation->id);
        Log::info('Generation status: '.$generation->status);
        Log::info('Generation file_path: '.$generation->file_path);

        if ($generation->status !== 'completed' || ! $generation->file_path) {
            Log::error('Generation not completed or no file path');
            abort(404, 'Documentation file not found or not completed');
        }

        if (! Storage::disk('local')->exists($generation->file_path)) {
            Log::error('File does not exist in storage: '.$generation->file_path);
            abort(404, 'Documentation file no longer exists');
        }

        $content = Storage::disk('local')->get($generation->file_path);
        $filename = $this->generateDownloadFilename($generation);
        $mimeType = $this->getMimeType($generation->format);

        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Length', strlen($content));
    }

    public function view($generationId): Response
    {
        $generation = DocumentationGeneration::findOrFail($generationId);

        if ($generation->status !== 'completed' || ! $generation->file_path) {
            abort(404, 'Documentation file not found or not completed');
        }

        if (! Storage::disk('local')->exists($generation->file_path)) {
            abort(404, 'Documentation file no longer exists');
        }

        // Only allow viewing of HTML and Markdown files
        if (! in_array($generation->format, ['html', 'markdown'])) {
            return $this->download($generation);
        }

        $content = Storage::disk('local')->get($generation->file_path);
        $mimeType = $this->getMimeType($generation->format);

        return response($content)
            ->header('Content-Type', $mimeType);
    }

    public function preview($generationId): Response
    {
        $generation = DocumentationGeneration::findOrFail($generationId);

        if ($generation->status !== 'completed' || ! $generation->file_path) {
            abort(404, 'Documentation file not found or not completed');
        }

        if (! Storage::disk('local')->exists($generation->file_path)) {
            abort(404, 'Documentation file no longer exists');
        }

        $content = Storage::disk('local')->get($generation->file_path);

        // For markdown, convert to simple HTML for preview
        if ($generation->format === 'markdown') {
            $htmlContent = $this->convertMarkdownToHtml($content, $generation);

            return response($htmlContent)->header('Content-Type', 'text/html');
        }

        // For HTML, return as-is
        if ($generation->format === 'html') {
            return response($content)->header('Content-Type', 'text/html');
        }

        // For PDF, redirect to download
        return $this->download($generation);
    }

    protected function generateDownloadFilename(DocumentationGeneration $generation): string
    {
        $title = Str::slug($generation->title);
        $timestamp = $generation->generated_at?->format('Y-m-d_H-i-s') ?? now()->format('Y-m-d_H-i-s');
        $extension = match ($generation->format) {
            'pdf' => 'pdf',
            'html' => 'html',
            default => 'md'
        };

        return "{$title}_{$timestamp}.{$extension}";
    }

    protected function getMimeType(string $format): string
    {
        return match ($format) {
            'pdf' => 'application/pdf',
            'html' => 'text/html',
            'markdown' => 'text/markdown',
            default => 'text/plain'
        };
    }

    protected function convertMarkdownToHtml(string $markdown, DocumentationGeneration $generation): string
    {
        // Simple markdown to HTML conversion for preview
        $html = $markdown;

        // Headers
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);

        // Bold
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);

        // Code
        $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);

        // Line breaks
        $html = nl2br($html);

        // Basic table conversion
        $html = $this->convertSimpleMarkdownTables($html);

        // Wrap in basic HTML structure
        return $this->wrapInPreviewHtml($html, $generation);
    }

    protected function convertSimpleMarkdownTables(string $html): string
    {
        $lines = explode("\n", $html);
        $inTable = false;
        $result = [];

        foreach ($lines as $line) {
            if (preg_match('/^\|(.+)\|$/', $line, $matches)) {
                if (! $inTable) {
                    $result[] = '<table style="border-collapse: collapse; width: 100%; margin: 20px 0;">';
                    $inTable = true;
                }

                $cells = array_map('trim', explode('|', trim($matches[1])));
                $row = '<tr>';
                foreach ($cells as $cell) {
                    $row .= '<td style="border: 1px solid #ddd; padding: 8px;">'.$cell.'</td>';
                }
                $row .= '</tr>';
                $result[] = $row;
            } elseif (preg_match('/^\|[-\s\|]+\|$/', $line)) {
                // Table separator line - convert previous row to header
                if (! empty($result) && $inTable) {
                    $lastRow = array_pop($result);
                    $headerRow = str_replace(['<td', '</td>'], ['<th', '</th>'], $lastRow);
                    $headerRow = str_replace('style="border: 1px solid #ddd; padding: 8px;"', 'style="border: 1px solid #ddd; padding: 8px; background-color: #f8f9fa; font-weight: bold;"', $headerRow);
                    $result[] = $headerRow;
                }
            } else {
                if ($inTable) {
                    $result[] = '</table>';
                    $inTable = false;
                }
                $result[] = $line;
            }
        }

        if ($inTable) {
            $result[] = '</table>';
        }

        return implode("\n", $result);
    }

    protected function wrapInPreviewHtml(string $content, DocumentationGeneration $generation): string
    {
        $title = htmlspecialchars($generation->title);

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - Preview</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6; 
            color: #333; 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
            background: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1, h2, h3 { 
            color: #2c3e50; 
            border-bottom: 2px solid #3498db; 
            padding-bottom: 10px; 
            margin-top: 30px;
        }
        h1 { font-size: 2.5em; margin-top: 0; }
        h2 { font-size: 2em; }
        h3 { font-size: 1.5em; }
        code { 
            background: #f1f1f1; 
            padding: 2px 5px; 
            border-radius: 3px; 
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace; 
        }
        .preview-notice {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            color: #1976d2;
        }
        .preview-notice strong {
            color: #0d47a1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="preview-notice">
            <strong>Preview Mode:</strong> This is a preview of your generated documentation. 
            <a href="#" onclick="window.close()">Close Preview</a>
        </div>
        {$content}
    </div>
</body>
</html>
HTML;
    }
}
