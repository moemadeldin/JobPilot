<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use App\Models\JobApplication;

final readonly class DeclineMockInterviewAction
{
    public function handle(JobApplication|CustomJobApplication $job): JobApplication|CustomJobApplication
    {
        $job->update(['mock_interview_status' => MockInterviewStatus::DECLINED->value]);

        return $job;
    }
}
