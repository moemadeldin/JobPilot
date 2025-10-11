<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\PasswordVerificationCodeSent;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

final class SendPasswordResetEmail
{
    /**
     * Handle the event.
     */
    public function handle(PasswordVerificationCodeSent $event): void
    {
        Mail::to($event->email)->queue(new PasswordResetMail($event->verificationCode));
    }
}
