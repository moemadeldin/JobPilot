<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
