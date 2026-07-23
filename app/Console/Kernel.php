<?php

namespace App\Console;

use App\Console\Commands\DiscountTimeExpire;
use App\Console\Commands\startAmazingDiscount;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\checkPayment::class,
        Commands\DiscountTimeExpire::class,
        startAmazingDiscount::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:checkPayment')->everyTenMinutes();
        $schedule->command('command:checkDiscountTime')->everyMinute();
        $schedule->command('start:discount')->everyMinute();

        // انتشارِ خودکارِ مقاله‌های زمان‌بندی‌شده (اگر cPanel cronِ «php artisan schedule:run» فعال باشد).
        $schedule->command('articles:publish-due')->everyFiveMinutes()->withoutOverlapping();
        // پشتیبان‌گیریِ روزانه‌ی دیتابیس.
        $schedule->command('db:backup')->dailyAt('03:00');
        // ضربانِ کرون: هر اجرا مهرِ زمانی در cache می‌گذارد تا ویجتِ «سلامتِ سیستم» در پنل
        // نشان دهد کرونِ cPanel زنده است یا از کار افتاده.
        $schedule->call(function () {
            cache()->forever('cron.last_run', now()->toIso8601String());
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
