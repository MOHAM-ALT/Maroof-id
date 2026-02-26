<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled email tasks
Schedule::command('mail:abandoned-carts')->dailyAt('10:00');
Schedule::command('mail:re-engagement')->weeklyOn(1, '09:00'); // كل اثنين الساعة 9 صباحاً
