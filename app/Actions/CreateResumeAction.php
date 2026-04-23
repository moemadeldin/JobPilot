<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\ExtractResumeTextJob;
use App\Models\Resume;
use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final readonly class CreateResumeAction
{
    public function handle(array $data, User $user): Resume
    {
        $resume = DB::transaction(function () use ($user, $data): Resume {
            if ($user->resume) {
                Storage::disk('public')->delete($user->resume->path);
            }

            $filePath = is_string($data['path'])
                ? $data['path']
                : $data['path']->storeAs(
                    Constants::RESUMES_PATH.'/'.$user->id,
                    Str::slug(pathinfo($data['path']->getClientOriginalName(), PATHINFO_FILENAME))
.'.'.$data['path']->getClientOriginalExtension(), 
'public'
                );

            return Resume::query()->updateOrCreate([
                'user_id' => $user->id,
            ],
                [
                    'name' => is_string($data['path'])
                        ? basename($data['path'])
                        : $data['path']->getClientOriginalName(),
                    'path' => $filePath,
                ]);
        });

        dispatch_sync(new ExtractResumeTextJob($resume));

        return $resume;
    }
}
