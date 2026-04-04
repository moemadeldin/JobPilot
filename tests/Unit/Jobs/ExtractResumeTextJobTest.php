<?php

declare(strict_types=1);

use App\Jobs\ExtractResumeTextJob;
use App\Models\Resume;
use App\Models\User;
use App\Services\ResumeTextExtractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Storage::fake('public');
    $this->user = User::factory()->create();
});

describe('ExtractResumeTextJob', function (): void {
    it('does not update when extraction returns null', function (): void {
        $resume = Resume::factory()->for($this->user)->create([
            'path' => 'resumes/nonexistent.pdf',
            'extracted_text' => null,
        ]);

        $job = new ExtractResumeTextJob($resume);
        $job->handle(resolve(ResumeTextExtractor::class));

        $resume->refresh();
        expect($resume->extracted_text)->toBeNull();
    });

    it('updates extracted text when extraction succeeds', function (): void {
        $resume = Resume::factory()->for($this->user)->create([
            'path' => 'resumes/test.pdf',
            'extracted_text' => null,
        ]);

        Storage::disk('public')->put('resumes/test.pdf', 'fake pdf content');

        $job = new ExtractResumeTextJob($resume);
        $job->handle(resolve(ResumeTextExtractor::class));

        $resume->refresh();
        expect($resume->extracted_text)->toBeNull();
    });
});
