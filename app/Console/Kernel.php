<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Jalankan cleanup notifikasi setiap hari pukul 01:00
        $schedule->command('notifications:cleanup')->dailyAt('01:00');

        // Atau jalankan setiap 6 jam untuk cleanup lebih frequent
        $schedule->command('notifications:cleanup --days=7')->everySixHours();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
