<?php

namespace App\Providers;

use App\Models\BeachClub;
use App\Models\Reservation;
use App\Policies\BeachClubPolicy;
use App\Policies\ReservationPolicy;
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
        Gate::policy(BeachClub::class, BeachClubPolicy::class);
        Gate::policy(Reservation::class, ReservationPolicy::class);
    }
}
