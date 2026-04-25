<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\MockInterviewStatus;
use App\Models\CustomJobApplication;
use Exception;

final readonly class DeclineMockInterviewAction
{
    public function handle(CustomJobApplication $job): CustomJobApplication
    {
        $mockInterview = $job->mockInterview;
        throw_if($mockInterview === null, Exception::class, 'Mock interview not found');
        $mockInterview->update(['status' => MockInterviewStatus::DISQUALIFIED->value]);

        return $job;
    }
}
