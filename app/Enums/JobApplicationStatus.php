<?php

declare(strict_types=1);

namespace App\Enums;

enum JobApplicationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REQUEST_ADDITIONAL_INFORMATION = 'request additional information';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::REQUEST_ADDITIONAL_INFORMATION => 'Request Additional Information',
        };
    }
}
