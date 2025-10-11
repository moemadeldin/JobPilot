<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ResumeTextExtractorInterface;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Throwable;

final class ResumeTextExtractor implements ResumeTextExtractorInterface
{
    public function extract(string $path): ?string
    {
        $fullPath = Storage::disk('public')->path($path);

        if (! is_file($fullPath)) {
            return null;
        }

        $text = $this->extractWithPdfParser($fullPath);
        if ($text) {
            return $this->cleanText($text);
        }

        $text = $this->extractWithPdftotext($fullPath);
        if ($text) {
            return $this->cleanText($text);
        }

        return $text ? $this->cleanText($text) : null;
    }

    private function extractWithPdfParser(string $file): ?string
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($file);
            $text = $pdf->getText();

            return trim($text) ?: null;
        } catch (Throwable) {
            return null;
        }
    }

    private function extractWithPdftotext(string $file): ?string
    {
        try {
            $cmd = 'pdftotext -layout '.escapeshellarg($file).' -';
            $output = shell_exec($cmd);

            return $output ? trim($output) : null;
        } catch (Throwable) {
            return null;
        }
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}
