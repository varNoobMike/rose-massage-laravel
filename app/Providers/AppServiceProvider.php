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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
        # URL::forceScheme('https');  // remove this line later if not using ngrok or not using https in production

        /**
         * Registering the ActivityLogObserver to observe the models for logging activities.
         */
        Announcement::observe(ActivityLogObserver::class);
        Booking::observe(ActivityLogObserver::class);
        Review::observe(ActivityLogObserver::class);
        Service::observe(ActivityLogObserver::class);
        User::observe(ActivityLogObserver::class);

        /**
         * Using a view composer to share the count of unread notifications with all views. 
         * This allows us to display the count of unread notifications in the UI, such as in a navbar or a notification icon, 
         * without having to pass this data from every controller method.
         */
        View::composer('*', function ($view) {
            $user = Auth::user();

            if (!$user) {
                $view->with('unreadNotificationsCount', 0);
                return;
            }

            $view->with(
                'unreadNotificationsCount',
                $user->unreadNotifications()->count()
            );
        });

        /**
         * Configuring the application to use Bootstrap 5 for pagination styling.
         */
        Paginator::useBootstrapFive();
    }
}
