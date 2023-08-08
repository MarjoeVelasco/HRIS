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
        //
        Commands\TimeReminder::class,
        Commands\DatabaseAttendanceBackup::class,
        Commands\DatabaseFullBackUp::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //send email reminder at 7am and 7pm to time in/out
        $schedule->command('command:timereminder')->twiceDaily(7, 19);

        //perform attendance backup daily midnight
        $schedule->command('database:attendance')->Daily();

        //perform weekly full backup every sunday midnight
        $schedule->command('database:full')->weekly();
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
