<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\UnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
        Route::get('/me', [AuthenticatedSessionController::class, 'me']);

        Route::apiResource('reservations', ReservationController::class);
        Route::apiResource('units', UnitController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('payments', PaymentController::class);
    });
});
