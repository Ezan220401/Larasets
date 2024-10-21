<?php

namespace App\Console;

use App\Models\Loan;
use App\Services\FonnteService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        $schedule->command('check:loan-almost-late')->everyThirtyMinutes();
        $schedule->command('check:loan-late')->everyTwoMinutes();
        $schedule->command('check:loan-pending')->timezone('Asia/Jakarta')->dailyAt('16.30');
        
        // $schedule->call(function(){
        //     $fonnteService = app(FonnteService::class);
        //     $loan = new Loan();
        //     $loan->loanCheck($fonnteService);
        // })->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
