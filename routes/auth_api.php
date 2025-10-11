<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\PasswordResetController;
use App\Http\Controllers\API\V1\Auth\SessionController;
use App\Http\Controllers\API\V1\JobController;
use App\Http\Controllers\API\V1\ResumeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::controller(SessionController::class)->group(function (): void {
        Route::delete('/logout', 'destroy')->name('logout.destroy');
        Route::get('/me', 'show')->name('me.show');
    });

    Route::controller(PasswordResetController::class)->group(function (): void {
        Route::post('/verify-code', 'checkCode')->name('verify.code');
        Route::post('/reset-password', 'resetPassword')->name('reset.password');
    });

    Route::controller(JobController::class)->group(function (): void {
        Route::get('/jobs', 'index')->name('jobs.index');
        Route::get('/jobs/{job}', 'show')->name('jobs.show');
        Route::post('/jobs/{job}', 'store')->name('jobs.store');
    });
    Route::post('/resumes', ResumeController::class)->name('resumes.store');

});
