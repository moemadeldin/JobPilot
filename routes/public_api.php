<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\PasswordResetController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::post('/register', RegisterController::class)
        ->name('register.store');
    Route::post('/login', [SessionController::class, 'store'])
        ->name('login.store');
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])
        ->name('forgot.store');
});
