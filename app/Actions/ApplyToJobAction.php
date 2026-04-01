<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\JobApplicationStatus;
use App\Jobs\EvaluateJobApplicationJob;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final readonly class ApplyToJobAction
{
    public function handle(User $user, JobVacancy $job, Resume $resume, ?string $coverLetter = null): JobApplication
    {
        $application = DB::transaction(fn (): JobApplication => JobApplication::query()->create([
            'user_id' => $user->id,
            'job_vacancy_id' => $job->id,
            'resume_id' => $resume->id,
            'cover_letter' => $coverLetter,
            'status' => JobApplicationStatus::PENDING->value,
            'applied_at' => now(),
        ]));
        Log::info('About to dispatch job for application', ['id' => $application->id]);

        dispatch_sync(new EvaluateJobApplicationJob($application));
        $application->refresh();

        return $application;
    }
}
