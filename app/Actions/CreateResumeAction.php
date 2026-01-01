<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\CreateResumeDTO;
use App\Jobs\ExtractResumeTextJob;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final readonly class CreateResumeAction
{
    public function handle(User $user, CreateResumeDTO $dto): Resume
    {
        return DB::transaction(function () use ($user, $dto): Resume {
            $filePath = is_string($dto->path)
                        ? $dto->path
                        : $dto->path->storeAs(
                            'resumes/'.$user->id,
                            Str::uuid().'.'.$dto->path->getClientOriginalExtension(),
                            'public'
                        );

            $resume = Resume::query()->create([
                'user_id' => $user->id,
                'name' => is_string($dto->path)
                    ? basename($dto->path)
                    : $dto->path->getClientOriginalName(),
                'path' => $filePath,
            ]);
            dispatch(new ExtractResumeTextJob($resume));

            return $resume;
        });

    }
}
