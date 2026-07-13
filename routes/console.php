<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\BookingReminderCommand;
use Illuminate\Support\Facades\Schedule;


Schedule::command(BookingReminderCommand::class)->everyMinute()->withoutOverlapping();


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('scheduler', function () {
    $this->call('schedule:run');
})->purpose('Run scheduled tasks');
