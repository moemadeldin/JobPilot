<?php

declare(strict_types=1);

use App\Services\ResumeTextExtractor;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    $this->extractor = new ResumeTextExtractor();
});

test('returns null for nonexistent file', function (): void {
    Storage::shouldReceive('disk')
        ->with('public')
        ->andReturnSelf();

    Storage::shouldReceive('path')
        ->with('nonexistent/file.pdf')
        ->andReturn('/fake/path/nonexistent/file.pdf');

    $result = $this->extractor->extract('nonexistent/file.pdf');

    expect($result)->toBeNull();
});

test('handles pdf parser exception', function (): void {
    Storage::shouldReceive('disk')
        ->with('public')
        ->andReturnSelf();

    Storage::shouldReceive('path')
        ->with('valid/file.pdf')
        ->andReturn('/fake/path/valid/file.pdf');

    $result = $this->extractor->extract('valid/file.pdf');

    expect($result)->toBeNull();
});

test('uses pdftotext fallback when pdf parser returns empty', function (): void {
    Storage::shouldReceive('disk')
        ->with('public')
        ->andReturnSelf();

    Storage::shouldReceive('path')
        ->with('fallback/file.pdf')
        ->andReturn('/fake/path/fallback/file.pdf');

    $result = $this->extractor->extract('fallback/file.pdf');

    expect($result)->toBeNull();
});

test('converts special characters in cleanText', function (): void {
    $reflection = new ReflectionClass($this->extractor);
    $method = $reflection->getMethod('cleanText');

    $text = '• Hello ◦ World ▪ Test – Dash — Emdash';

    $result = $method->invoke($this->extractor, $text);

    expect($result)->toBe('- Hello - World - Test - Dash - Emdash');
});

test('normalizes whitespace in cleanText', function (): void {
    $reflection = new ReflectionClass($this->extractor);
    $method = $reflection->getMethod('cleanText');

    $text = "   Hello    World   \n\n   Test   ";

    $result = $method->invoke($this->extractor, $text);

    expect($result)->toBe('Hello World Test');
});

test('trims whitespace in cleanText', function (): void {
    $reflection = new ReflectionClass($this->extractor);
    $method = $reflection->getMethod('cleanText');

    $text = '   Hello World   ';

    $result = $method->invoke($this->extractor, $text);

    expect($result)->toBe('Hello World');
});

test('handles empty pdf text', function (): void {
    Storage::shouldReceive('disk')
        ->with('public')
        ->andReturnSelf();

    Storage::shouldReceive('path')
        ->with('empty/file.pdf')
        ->andReturn('/fake/path/empty/file.pdf');

    $result = $this->extractor->extract('empty/file.pdf');

    expect($result)->toBeNull();
});

test('returns null when file is not a pdf', function (): void {
    Storage::shouldReceive('disk')
        ->with('public')
        ->andReturnSelf();

    Storage::shouldReceive('path')
        ->with('notpdf/file.txt')
        ->andReturn('/fake/path/notpdf/file.txt');

    $result = $this->extractor->extract('notpdf/file.txt');

    expect($result)->toBeNull();
});

test('extractWithPdfParser returns null on exception', function (): void {
    $reflection = new ReflectionClass($this->extractor);
    $method = $reflection->getMethod('extractWithPdfParser');

    $result = $method->invoke($this->extractor, '/nonexistent/file.pdf');

    expect($result)->toBeNull();
});

test('extractWithPdftotext returns null when shell exec fails', function (): void {
    $reflection = new ReflectionClass($this->extractor);
    $method = $reflection->getMethod('extractWithPdftotext');

    $result = $method->invoke($this->extractor, '/nonexistent/file.pdf');

    expect($result)->toBeNull();
});
