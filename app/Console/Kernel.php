<?php

namespace App\Console;

use Carbon\Carbon;
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

        $since = Carbon::parse('10 min ago')->toIso8601String();

        $schedule->command("import:aggregator --since '{$since}'")
            ->everyTenMinutes()
            ->withoutOverlapping(self::FOR_ONE_YEAR);

        $since = Carbon::parse('1 day ago')->toIso8601String();

        $schedule->command("import:aggregator --since '{$since}'")
            ->daily()
            ->withoutOverlapping(self::FOR_ONE_YEAR);

        $before = Carbon::parse('72 hours ago')->toIso8601String();

        $schedule->command("csv:clear --before '{$before}'")
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
