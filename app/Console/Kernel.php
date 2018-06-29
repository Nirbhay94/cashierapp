<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('invoice:repeat')->hourly()->withoutOverlapping();

        $schedule->command('subscriptions:renew')->hourly()->withoutOverlapping();

        if(config('currency.api_key')){
            $schedule->command('currency:update --openexchangerates')->hourly();
        }else{
            $schedule->command('currency:update --google')->hourly();
        }

        $schedule->call(function(){Cache::forever('cron_last_run', Carbon::now());})->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
