<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\CreateResumeDTO;
use App\Jobs\ExtractResumeTextJob;
use App\Models\Resume;
use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final readonly class CreateResumeAction
{
    public function handle(User $user, CreateResumeDTO $dto): Resume
    {
        $resume = DB::transaction(function () use ($user, $dto): Resume {
            if ($user->resume) {
                Storage::disk('public')->delete($user->resume->path);
            }

            $filePath = is_string($dto->path)
                ? $dto->path
                : $dto->path->storeAs(
                    Constants::RESUMES_PATH.'/'.$user->id,
                    Str::slug(pathinfo($dto->path->getClientOriginalName(), PATHINFO_FILENAME))
.'.'.$dto->path->getClientOriginalExtension(), 
'public'
                );

            return Resume::query()->updateOrCreate([
                'user_id' => $user->id,
            ],
                [
                    'name' => is_string($dto->path)
                        ? basename($dto->path)
                        : $dto->path->getClientOriginalName(),
                    'path' => $filePath,
                ]);
        });

        dispatch_sync(new ExtractResumeTextJob($resume));

        return $resume;
    }
}
