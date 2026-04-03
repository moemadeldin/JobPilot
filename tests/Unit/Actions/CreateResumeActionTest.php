<?php

declare(strict_types=1);

use App\Actions\CreateResumeAction;
use App\DTOs\CreateResumeDTO;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
    Queue::fake();
});

describe('CreateResumeAction', function (): void {
    it('creates a resume from uploaded file', function (): void {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('resume.pdf', 1024);

        $dto = new CreateResumeDTO(path: $file);
        $action = app(CreateResumeAction::class);

        $resume = $action->handle($user, $dto);

        expect($resume)->toBeInstanceOf(Resume::class);
        expect($resume->user_id)->toBe($user->id);
        expect($resume->name)->toBe('resume.pdf');
        expect($resume->path)->toContain('resumes/'.$user->id);
    });

    it('creates resume with string path', function (): void {
        $user = User::factory()->create();
        $filePath = 'resumes/test.pdf';

        $dto = new CreateResumeDTO(path: $filePath);
        $action = app(CreateResumeAction::class);

        $resume = $action->handle($user, $dto);

        expect($resume)->toBeInstanceOf(Resume::class);
        expect($resume->path)->toBe($filePath);
        expect($resume->name)->toBe('test.pdf');
    });

    it('dispatches ExtractResumeTextJob after creating resume', function (): void {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('resume.pdf', 1024);

        $dto = new CreateResumeDTO(path: $file);
        $action = app(CreateResumeAction::class);

        $resume = $action->handle($user, $dto);

        Queue::assertPushed(\App\Jobs\ExtractResumeTextJob::class);
    });
});