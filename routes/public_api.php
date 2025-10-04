<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->middleware('guest')
    ->group(function (): void {
        Route::post('/register', 'register')->name('register.post');
        Route::post('/login', 'login')->name('login.post');
    });
