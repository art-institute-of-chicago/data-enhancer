<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * WEB-874: Make commands never overlap.
     */
    private const FOR_ONE_YEAR = 525600;

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:cloudfront-ips')
            ->hourly();

        $schedule->command('import:aggregator --since "10 min ago"')
            ->everyFiveMinutes()
            ->withoutOverlapping(self::FOR_ONE_YEAR);
    }

    /**
     * Register the Closure based commands for the application.
     * By default, it loads all commands in `Commands` non-recursively.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
