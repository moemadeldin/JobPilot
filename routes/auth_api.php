<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\PasswordResetController;
use App\Http\Controllers\API\V1\Auth\SessionController;
use App\Http\Controllers\API\V1\CoverLetterController;
use App\Http\Controllers\API\V1\CustomApplicationController;
use App\Http\Controllers\API\V1\CustomJobVacancyController;
use App\Http\Controllers\API\V1\CustomMockInterviewController;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\ProfilePasswordController;
use App\Http\Controllers\API\V1\ResumeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::controller(SessionController::class)->group(function (): void {
        Route::delete('/logout', 'destroy')
            ->name('logout.destroy');
        Route::get('/me', 'show')
            ->name('me.show');
    });

    Route::controller(PasswordResetController::class)->group(function (): void {
        Route::post('/verify-code', 'checkCode')
            ->name('verify.code');
        Route::post('/reset-password', 'resetPassword')
            ->name('reset.password');
    });

    Route::controller(ProfileController::class)->group(function (): void {
        Route::get('/profile', 'index')
            ->name('profile.index');
        Route::post('/profile', 'store')
            ->name('profile.store');
        Route::put('/profile', 'update')
            ->name('profile.update');
        Route::delete('/profile', 'destroy')
            ->name('profile.destroy');
    });

    Route::post('/profile/password', ProfilePasswordController::class)
        ->name('profile.password');

    Route::controller(CustomJobVacancyController::class)->group(function (): void {
        Route::get('custom-vacancies', 'index')
            ->name('custom-vacancies.index');
        Route::post('custom-vacancies', 'store')
            ->name('custom-vacancies.store');
        Route::get('custom-vacancies/{customJobVacancy}', 'show')
            ->name('custom-vacancies.show');
        Route::delete('custom-vacancies/{customJobVacancy}', 'destroy')
            ->name('custom-vacancies.destroy');
    });

    Route::controller(CustomApplicationController::class)->group(function (): void {
        Route::get('custom-applications/', 'index')
            ->name('custom-vacancies.apply');
        Route::get('custom-applications/{customApplication}', 'show')
            ->name('custom-vacancies.show');
        Route::post('custom-vacancies/{customJobVacancy}/apply', 'store')
            ->name('custom-vacancies.apply');

    });
    Route::controller(CustomMockInterviewController::class)->group(function (): void {
        Route::post('custom-applications/{customApplication}/mock/accept', 'store')
            ->name('custom-applications.mock.store');
        Route::delete('custom-applications/{customApplication}/mock/decline', 'destroy')
            ->name('custom-applications.mock.destroy');
        Route::get('custom-applications/{customApplication}/mock', 'show')
            ->name('custom-applications.mock.show');
    });
    Route::post('custom-vacancies/{customJobVacancy}/cover-letter', CoverLetterController::class)
        ->name('custom-vacancies.cover-letter');


    Route::controller(ResumeController::class)->group(function (): void {
        Route::get('/resumes', 'index')
            ->name('resumes.index');

        Route::post('/resumes', 'store')
            ->name('resumes.store');
    });
});
