<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use function PHPSTORM_META\map;

class DocumentController extends Controller
{

    public function assetRecap() {
        // Mengelompokkan data aset
        $assets = Asset::all();
        $roomAsset = Asset::where('asset_code', 'LIKE', '%170.00%')->get();
        $toolAsset = Asset::whereNotIn('category_id', [1, 3, 7])->get();
        $stuffAsset = Asset::where('asset_code', 'LIKE', '%170.05%')->get();
        $vehicleAsset = Asset::where('asset_code', 'LIKE', '%170.03%')->get();
        
        return view('recap.assetRecap', compact(
            'roomAsset', 'toolAsset', 'stuffAsset', 'vehicleAsset' 
        ));
    }
    
    private function forecastNextMonth() {
        // Mengambil data peminjaman 6 bulan terakhir
        $months = Carbon::now()->subMonths(6);
        $loans = Loan::where('created_at', '>=', $months)->get();
    
        // Mengelompokkan data peminjaman berdasarkan bulan
        $monthlyLoans = $loans->groupBy(function ($loan) {
            return Carbon::parse($loan->loan_date)->format('M-Y');
        });
    
        // Mencoba mengkalkulasi data dari 3 bulan untuk mendapat data peramalan data selanjutnya
        $monthlyLoanCounts = $monthlyLoans->map->count();
        $smaPeriod = 3;
        $sma = $this->calculateSMA($monthlyLoanCounts, $smaPeriod);
    
        // Memastikan ada data SMA sebelum melakukan prediksi
        $nextMonthPrediction = !empty($sma) ? end($sma) : 0;
    
        return $nextMonthPrediction;
    }

    private function calculateSMA(Collection $data, int $period){
        $sma = [];
    
        for($i = 0; $i <= count($data) - $period-1; $i++) {
            $window = $data->slice($i, $period);
            $average = $window->sum() / $period; //rumus SMA
            $sma[] = $average;
        }
    
        return $sma;
    }    

    public function dataRecap(Request $request)
    {
        // Bagian Aset
        $asset_categories = Category::all();
        $assets = Asset::query();
        $assetItemsPerPage = $request->input('asset_items_per_page', 10); // default 10

        if ($request->has('search_asset')) {
            $assets->where(function($query) use ($request) {
                $query->where('asset_name', 'like', '%' . $request->search_asset . '%')
                    ->orWhere('asset_desc', 'like', '%' . $request->search_asset . '%');
            });
        }

        if ($request->has('asset_categories') && $request->asset_categories != 'all') {
            $assets->where('category_id', $request->asset_categories);
        }

        $assets = $assets->with('category')->orderBy('asset_date_of_entry', 'desc')->paginate($assetItemsPerPage);

        // Bagian Peminjaman
        $loan_categories = LoanCategory::all();
        $loans = Loan::query();
        $loanItemsPerPage = $request->input('loan_items_per_page', 10); // default 10

        if ($request->has('search_loan')) {
            $loans->where(function($query) use ($request) {
                $query->where('loan_asset_name', 'like', '%' . $request->search_loan . '%')
                    ->orWhere('loan_desc', 'like', '%' . $request->search_loan . '%')
                    ->orWhere('applicant_name', 'like', '%' . $request->search_loan . '%');
            });
        }

        if ($request->has('loan_categories') && $request->loan_categories != 'all') {
            $loans->where('loan_name', $request->loan_categories);
        }

        $loans = $loans->with('loan_category')->orderBy('created_at', 'desc')->paginate($loanItemsPerPage);

        // Bagian Evaluasi
        $months = Carbon::now()->subMonths(6);
        $loans_data = Loan::where('created_at', '>=', $months)->orderBy('created_at', 'asc')->get(); 

        $mostFrequentLoans = $loans_data->groupBy('loan_asset_name')
                            ->map(function($group) {
                                return $group->count();
                            })
                            ->filter(function($count) {
                                return $count >= 2; // hanya yang dipinjam lebih dari atau sama dengan 2 kali
                            })
                            ->sortDesc();

        $weeklyLoans = $loans_data->groupBy(function($loan) {
        // Gunakan objek Carbon langsung sebagai kunci
            return Carbon::parse($loan->created_at);
        })->sortKeys();
                            
        $timelyLoanCounts = $weeklyLoans->map->count();

        // Pengelompokan mingguan
        $weeklyLoans = $loans_data->groupBy(function ($loan) {
            return Carbon::parse($loan->created_at)->startOfWeek()->format('Y-m-d');
        })->sortKeys(); 
        $weeklyLoanCounts = $weeklyLoans->map->count();

        // Pengelompokan bulan untuk  mengurutkan bulan (kadang jadi berantakan kalau tidak diurutkan berdasarkan bulan)
        $monthlyLoans = $loans_data->groupBy(function ($loan) {
            return Carbon::parse($loan->created_at)->format('M-Y');
        })->sortKeys(); // Urutkan kunci setelah grouping
        $monthlyLoanCounts = $monthlyLoans->map->count();

        // Analisa Tren
        $sma = collect($this->calculateSMA($weeklyLoanCounts, 3)); // 3 minggu kebelakang
        $sma5 = collect($this->calculateSMA($weeklyLoanCounts, 5)); // 5 minggu kebelakang
        $trend = ($sma->last() > $sma->first()) ? 'increasing' : 'decreasing';

        $weekLabels = $weeklyLoans->keys()->map(function($week) {
            return Carbon::parse($week)->format('d-m-Y');
        });

        $averageSMA = $sma->average();
        $peaks = $sma->filter(function ($value) use ($averageSMA) {
            return $value > $averageSMA;
        })->keys();
        $peekOnDate = $weeklyLoans->keys()->toArray();

        $weeklyAssets = $weeklyLoans->map(function ($loans_data) {
            return $loans_data->groupBy('loan_asset_name')->map->count();
        });

        // Forecasting
        $now = Carbon::now()->format('Y-m-d');
        $asset_data = Asset::all();
        $nextPeriodPrediction = $this->forecastNextMonth();

        // Pengelompokan mingguan untuk label
        $mappedWeeklyLoanCounts = [];

        foreach ($weekLabels as $week) {
            $weekNumber = Carbon::parse($week)->weekOfYear;
            $mappedWeeklyLoanCounts[] = $weeklyLoanCounts->get($weekNumber, 0);
        }

        $medianLoanCounts = $weeklyLoanCounts->median();

        // Grafik
        $weeks = $weeklyLoanCounts->keys(); 
        $actualData = $weeklyLoanCounts->values();
        $shiftedSma3 = array_pad($sma->toArray(), -count($sma) - 3, null); 
        $shiftedSma5 = array_pad($sma5->toArray(), -count($sma5) - 5, null);

        $sma3forcecast = floor(end($shiftedSma3));
        $sma5forcecast= floor(end($shiftedSma5));

        // Menghitung MPE untuk SMA 3 periode
        $mpeSma3 = collect($actualData)->zip($sma)->map(function($pair) {
            return (($pair[0] - $pair[1]) / $pair[0]) * 100; //rumus MPE
        })->average();

        // Menghitung MPE untuk SMA 5 periode
        $mpeSma5 = collect($actualData)->zip($sma5)->map(function($pair) {
            return (($pair[0] - $pair[1]) / $pair[0]) * 100; //rumus MPE
        })->average();

        $mpeSma3 = round($mpeSma3, 1);
        $mpeSma5 = round($mpeSma5, 1);
        // Kembalian ke View
        return view('recap.index', compact('loans', 'assets', 'asset_categories', 'loan_categories', 'peaks', 'peekOnDate',
            'now', 'sma', 'mostFrequentLoans', 'averageSMA', 'trend', 'weekLabels', 'weeklyAssets',
            'sma3forcecast', 'sma5forcecast','nextPeriodPrediction', 'mappedWeeklyLoanCounts',  'asset_data', 'shiftedSma3', 'shiftedSma5',
            'weeks', 'actualData', 'timelyLoanCounts', 'mpeSma3', 'mpeSma5'));
    }

    
    public function adminRecap() {
        $admins = User::all()->filter(function($admin) {
            return $admin->group_id >= 1 && $admin->group_id <= 8;
        });
    
        return view('letters.recap', compact('admins'));
    }

    public function applicantRecap() {
        $users = User::all()->filter(function($user) {
            return $user->group_id >= 1 && $user->group_id <= 8;
        });
    
        return view('letters.recap', compact('users'));
    }
    
    public function loanRecap(Request $request) {
        $filterOption = $request->input('filter_option');
        $endDate = Carbon::now()->endOfDay(); 
        $startDate = $request->input('start_date', Carbon::now()->subYears(4)->startOfDay());
    
        if ($filterOption == 'all') {
            $roomLoan = Loan::whereIn('loan_name', ['Peminjaman Ruangan', 'Peminjaman Laboratorium'])->get();
            $toolLoan = Loan::where('loan_name', 'Peminjaman Alat')->get();
            $stuffLoan = Loan::where('loan_name', 'Peminjaman Barang')->get();
            $vehicleLoan = Loan::where('loan_name', 'Peminjaman Kendaraan')->get();
        } else {
            $roomLoan = Loan::whereIn('loan_name', ['Peminjaman Ruangan', 'Peminjaman Laboratorium'])
                            ->whereBetween('loan_date', [$startDate, $endDate])->get();
            $toolLoan = Loan::where('loan_name', 'Peminjaman Alat')
                            ->whereBetween('loan_date', [$startDate, $endDate])->get();
            $stuffLoan = Loan::where('loan_name', 'Peminjaman Barang')
                            ->whereBetween('loan_date', [$startDate, $endDate])->get();
            $vehicleLoan = Loan::where('loan_name', 'Peminjaman Kendaraan')
                            ->whereBetween('loan_date', [$startDate, $endDate])->get();
        }
    
        return view('recap.loanRecap', compact('roomLoan', 'toolLoan', 'stuffLoan', 'vehicleLoan'));
    }
    

    public function label($asset_id){
        $asset = Asset::findOrFail($asset_id);
        $asset_name = $asset->asset_name;
        $asset_code = $asset->asset_code;
        $updatedAt = Carbon::parse($asset->updated_at)->format('d-m-Y');
    
        $asset_code_parts = explode('(', $asset_code);
        $categories = Category::all();
        $asset_code_prefix = $asset_code_parts[0];
            
        $codes = [];
        $qrcode = QrCode::size(100)->backgroundColor(255, 255, 255)->color(0, 0, 0)->margin(1)
                    ->generate(
                        $asset_name .
                        '. Deskripsi: ' . $asset->asset_desc . '. Terdaftar sejak: ' . $asset->asset_date_of_entry . ' dengan kode BKK ' . $asset->receipt_number. 
                        '. Disimpan di ' . $asset->asset_position .
                        '. Info Lengkap: http://localhost:8000/asset/' . $asset->asset_id .' ' .
                        ' Update terakhir pada: '. $updatedAt);
        
        for ($i = 1; $i <= $asset->asset_quantity; $i++) {
            $codes[] = $asset_code_parts[0] . '.' . str_pad($i, strlen((string)$asset->asset_quantity), '0', STR_PAD_LEFT);
        }
    
        return view('recap.label', compact('asset', 'qrcode', 'codes'));
    }
    
    public function letter($loan_id) {
        $loans = Loan::findOrFail($loan_id);
        $asset = Asset::all();
        $applicant = $loans->applicant_name;
        
        $updatedAt = Carbon::parse($loans->updated_at)->format('mdY');
        $createdAt = Carbon::parse($loans->created_at)->format('mdY');
        $loanLength = Carbon::parse($loans->loan_length)->format('d-m-Y');

        switch ($loans->loan_name) {
            case 'Peminjaman Alat':
                $assetFiltered = Loan::where('applicant_name', $applicant)   
                        ->where('loan_name', 'Peminjaman Alat')
                        ->orWhere('loan_id', $loans->loan_id)->get(); //bisa banyak
                return view('letters.toolSubmission', compact('loans', 'assetFiltered', 'updatedAt', 'loanLength', 'createdAt'));
            
            case 'Peminjaman Barang':
                $assetFiltered = Loan::where('applicant_name', $applicant)
                        ->where('loan_name', 'Peminjaman Barang')
                        ->orWhere('loan_id', $loans->loan_id)->get(); //bisa banyak
                                
                return view('letters.stuffSubmission', compact('loans', 'assetFiltered', 'updatedAt', 'loanLength', 'createdAt'));
            
            case 'Peminjaman Ruangan':
                $assetFiltered = Loan::where('applicant_name', $applicant)
                        ->where('loan_name', 'Peminjaman Ruangan')
                        ->orWhere('loan_id', $loans->loan_id)->first();
                                
                return view('letters.roomSubmission', compact('loans', 'assetFiltered', 'updatedAt', 'loanLength', 'createdAt'));
            
            case 'Peminjaman Kendaraan':
                $assetFiltered = Loan::where('applicant_name', $applicant)
                        ->where('loan_name', 'Peminjaman Kendaraan')    
                        ->where('created_at', $loans->created_at)
                        ->orWhere('loan_id', $loans->loan_id)->first();
                        
                return view('letters.vehicleSubmission', compact('loans', 'assetFiltered', 'updatedAt', 'loanLength', 'createdAt'));
            
                case 'Peminjaman Laboratorium':
                    $assetFiltered = Loan::where('applicant_name', $applicant)
                        ->where('loan_name', 'Peminjaman Laboratorium')
                        ->orWhere('loan_id', $loans->loan_id)->first();
                        
                    return view('letters.laboratorySubmission', compact('loans', 'assetFiltered', 'updatedAt', 'loanLength', 'createdAt'));
        
            default:
                abort(404, 'Jenis peminjaman tidak ditemukan.');
        }
    }   
}