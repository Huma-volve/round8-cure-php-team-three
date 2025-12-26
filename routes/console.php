<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;



Schedule::command('booking:cleanup-reservations')->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('notifications:send-upcoming-custom', function () {
    $this->call('notifications:send-upcoming');
})->purpose('Custom wrapper for sending upcoming notifications');
