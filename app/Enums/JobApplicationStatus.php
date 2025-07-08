<?php

declare(strict_types=1);

namespace App\Enums;

enum JobApplicationStatus: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case REJECTED = 2;
    case REQUEST_ADDITIONAL_INFORMATION = 3;

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
