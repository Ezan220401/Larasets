<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckAlmostLateReturn extends Command
{
    protected $signature = 'check:loan-almost-late';
    protected $description = 'Checking loan expire reminder and sent message';
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        parent::__construct();
        $this->fonnteService = $fonnteService;
    }
    public function handle()
    {
        $oneHourAgo = Carbon::now()->subHour();
        $loans = Loan::where('loan_length', '<=', $oneHourAgo)
                     ->where('is_using', true)            
                     ->where('is_returned', false)
                     ->get();

        foreach ($loans as $loan) {
            $message = $loan->applicant_name . ', *waktu peminjaman ' . $loan->loan_asset_name . ' anda akan segera habis*, harap bersiap mengembalikan!';
            $countryCode = '62';
            $applicantPhone = '089688355159'; //percobaan
            // $applicantPhone = $loan->applicant_phone;
            $response = $this->fonnteService->sendMessage($applicantPhone, $message, $countryCode);
            $this->info('Message sent: '. $response);
        }
    }
}
