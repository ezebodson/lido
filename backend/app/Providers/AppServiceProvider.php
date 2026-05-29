<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Unit;
use App\Policies\CustomerPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ReservationPolicy;
use App\Policies\UnitPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Reservation::class, ReservationPolicy::class);
        Gate::policy(Unit::class, UnitPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
    }
}
