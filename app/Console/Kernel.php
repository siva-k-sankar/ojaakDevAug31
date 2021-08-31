<?php

namespace App\Console;

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
        Commands\WalletCron::class,
        Commands\ValiditiyCron::class,
        Commands\OjaakpointCron::class,
        Commands\WalletValidityCron::class,
        Commands\UserVerifiedCron::class,
        Commands\BroadcastCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('wallet:cron')->monthlyOn(1, '00:05');
        //$schedule->command('wallet:cron')->everyMinute();
        $schedule->command('validitiy:cron')->everyMinute();
        $schedule->command('ojaakpoint:cron')->everyMinute();
        $schedule->command('walletvalidity:cron')->everyMinute();
        $schedule->command('UserVerified:cron')->everyMinute();
        //$schedule->command('Broadcast:send')->dailyAt('10:00');
        $schedule->command('Broadcast:send')->everyMinute();
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
