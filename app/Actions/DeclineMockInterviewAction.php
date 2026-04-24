<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;

final readonly class DeclineMockInterviewAction
{
    public function handle(CustomJobApplication $job): CustomJobApplication
    {
        $job->mockInterview->update(['status' => MockInterviewStatus::DISQUALIFIED->value]);

        return $job;
    }
}
