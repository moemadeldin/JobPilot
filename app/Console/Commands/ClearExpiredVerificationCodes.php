<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

final class ClearExpiredVerificationCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codes:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clears expired verification code';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::query()->whereNotNull('verification_code')
            ->where('verification_code_expire_at', '<', now())
            ->update([
                'verification_code' => null,
                'verification_code_expire_at' => null,
            ]);
    }
}
