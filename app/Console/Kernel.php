<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('expirar:consultas')->daily();
        $schedule->command('expirar:copias')->daily();
        $schedule->command('expirar:avisos')->daily();
        $schedule->command('backup:run')->daily()->at('01:30');
        /* $schedule->command('reasignar:usuario')->daily(); */
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
