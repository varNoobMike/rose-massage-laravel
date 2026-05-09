<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Actions\Booking\SyncBookingStatuses;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Auto sync booking statuses
 */
Schedule::call(function () {
    app(SyncBookingStatuses::class)->execute();
})->everyMinute();