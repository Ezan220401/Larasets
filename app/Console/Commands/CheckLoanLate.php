<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckLoanLate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:loan-late';
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
            $message = $loan->applicant_name . ', *waktu peminjaman ' . $loan->loan_asset_name . ' anda sudah habis*, anda terlambat mengembalikannya. harap kembalikan ' . $loan->loan_asset_name . ' tersebut pada pihak kampus dengan segera!';
            $countryCode = '62';
            $applicantPhone = '089688355159'; //percobaan
            $Disable_applicantPhone = $loan->applicant_phone;
            $response = $this->fonnteService->sendMessage($applicantPhone, $message, $countryCode);
            $this->info('Message sent: '. $response);
        }
        
        // foreach($expiredLoans as $loan){
        //     Mail::to($loan->user->user_email)->send(new LoanExpiryReminder($loan));
        // }
    }
}
