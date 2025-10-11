<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\CreateResumeDTO;
use App\Interfaces\ResumeTextExtractorInterface;
use App\Jobs\ExtractResumeTextJob;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Str;

final readonly class CreateResumeAction
{
    public function __construct(private ResumeTextExtractorInterface $extractor) {}

    public function handle(User $user, CreateResumeDTO $dto): Resume
    {
        $filePath = is_string($dto->path)
            ? $dto->path
            : $dto->path->storeAs(
                'resumes/'.$user->id,
                Str::uuid().'.'.$dto->path->getClientOriginalExtension(),
                'public'
            );

        $resume = Resume::create([
            'user_id' => $user->id,
            'name' => is_string($dto->path)
                ? basename($dto->path)
                : $dto->path->getClientOriginalName(),
            'path' => $filePath,
        ]);

        ExtractResumeTextJob::dispatch($resume);

        return $resume;
    }
}
