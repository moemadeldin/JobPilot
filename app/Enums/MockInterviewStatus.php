<?php

declare(strict_types=1);

namespace App\Enums;

enum MockInterviewStatus: string
{
    case SUGGESTED = 'suggested';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case DISQUALIFIED = 'disqualified';

    public function label(): string
    {
        return match ($this) {
            self::SUGGESTED => 'Suggested',
            self::ACCEPTED => 'Accepted',
            self::DECLINED => 'Declined',
            self::DISQUALIFIED => 'Disqualified',
        };
    }
}
