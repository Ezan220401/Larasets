<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\User;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckPendingLoans extends Command
{
    protected $signature = 'check:loan-pending';
    protected $description = 'Send a pending allert message via Fonnte API';
    protected $fonnteService ;

    public function __construct(FonnteService $fonnteService)
    {
        parent::__construct();
        $this->fonnteService = $fonnteService;
    }

    public function handle()
    {

        $now = Carbon::now();
        $tomorrow = $now->copy()->addDays(1);
        
        $loans = Loan::where('is_full_approve', false)
                     ->where('loan_date', '>=', $now)
                     ->where('loan_date', '<=', $tomorrow)
                     ->get();
        $adminUsers = '089688355159'; //percobaan
        // $adminUsers = User::whereNotIn('group_id', [5, 6, 7, 8, 9, 99])
        //             ->pluck('user_phone');
        

        foreach ($loans as $loan) {
            $message = 'Cek pengajuan *peminjaman ' . $loan->loan_asset_name . '*, yang diajukan oleh ' . $loan->applicant_name . '!';
            $countryCode = '62';
            $response = $this->fonnteService->sendMessage($adminUsers, $message, $countryCode);

            // foreach ($adminUsers as $userPhone) {
            //     $response = $this->fonnteService->sendMessage($userPhone, $message, $countryCode);
            //     $this->info('Message sent: ' . $response);
            // }
        }
    }
}
