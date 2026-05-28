<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BeachClubController;
use App\Http\Controllers\Api\V1\CalendarController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\RateController;
use App\Http\Controllers\Api\V1\ReservationController;
use App\Http\Controllers\Api\V1\SectorController;
use App\Http\Controllers\Api\V1\UnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'tenant'])->group(function (): void {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('dashboard/metrics', [DashboardController::class, 'metrics']);
        Route::get('calendar/occupancy', [CalendarController::class, 'occupancy']);
        Route::get('units/map', [UnitController::class, 'map']);

        Route::apiResource('beach-clubs', BeachClubController::class);
        Route::apiResource('sectors', SectorController::class);
        Route::apiResource('units', UnitController::class);
        Route::apiResource('customers', CustomerController::class);

        Route::middleware('role:super_admin,beach_admin,reception')->group(function (): void {
            Route::apiResource('reservations', ReservationController::class);
        });

        Route::middleware('role:super_admin,beach_admin,cashier')->group(function (): void {
            Route::apiResource('payments', PaymentController::class);
        });

        Route::middleware('role:super_admin,beach_admin')->group(function (): void {
            Route::apiResource('rates', RateController::class);
        });
    });
});
