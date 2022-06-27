<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('facebook:insight')->everyMinute();
        $schedule->command('google:insight')->everyMinute();
        $schedule->command('ab:facebook')->everyMinute();
        $schedule->command('ab:googleText')->everyMinute();
        $schedule->command('ab:googleImage')->everyMinute();
        $schedule->command('ab:googleResponsiveImage')->everyMinute();
        $schedule->command('update:googleToken')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
