<?php

namespace App\Models;

use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Loan extends Model
{
    use HasFactory;
    protected $primaryKey = 'loan_id';
    protected $guarded = ['loan_id'];

    public function asset(){
        // return $this->hasMany(Loan::class, 'asset_id');
        return $this->belongsTo(Asset::class, 'asset_id', 'asset_id');
    }

    public function using(){
        return $this->hasOne(Using::class, 'using_id', 'using_id');
    }

    public function returning(){
        return $this->hasOne(Returning::class, 'return_id', 'return_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function loan_category(){
        return $this->belongsTo(LoanCategory::class, 'category_name');
    }

    public function loanCheck(FonnteService $fonnteService){
        $oneHourAgo = Carbon::now()->subHour();
        $loans = Loan::where('loan_length', '<=', $oneHourAgo)
                     ->where('is_using', true)            
                     ->where('is_returned', false)
                     ->get();

        foreach ($loans as $loan) {
            $message = $loan->applicant_name . ', *waktu peminjaman ' . $loan->loan_asset_name . ' anda sudah habis*, anda terlambat mengembalikannya. harap kembalikan ' . $loan->loan_asset_name . ' tersebut pada pihak kampus dengan segera!';
            $countryCode = '62';
            $applicantPhone = '089688355159'; //percobaan
            // $applicantPhone = $loan->applicant_phone;
            $response = $fonnteService->sendMessage($applicantPhone, $message, $countryCode);
            Log::info('Message sent: '. $response);
        }
    }
}

