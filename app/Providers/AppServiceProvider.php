<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

use App\Models\Announcement;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Review;
use App\Models\User;
use App\Observers\ActivityLogObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        URL::forceScheme('https');  // remove this line later if not using ngrok

        Announcement::observe(ActivityLogObserver::class);
        Booking::observe(ActivityLogObserver::class);
        Review::observe(ActivityLogObserver::class);
        Service::observe(ActivityLogObserver::class);
        User::observe(ActivityLogObserver::class);
    }
}
