<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Returning;
use App\Models\UserGroup;
use App\Models\Using;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userNumberId = Auth::user()->user_number_id;
        $groups = UserGroup::all();

        // Mulai query dari model Loan
        $query = Loan::where('applicant_number_id', $userNumberId);

        // Cek apakah ada request pencarian
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('loan_asset_name', 'like', "%{$search}%")
                ->orWhere('loan_desc', 'like', "%{$search}%");
            });
        }

        // Pagination
        $itemsPerPage = $request->input('items_per_page', 5); //default = 10
        $loans = $query->latest()->paginate($itemsPerPage, ['*'], 'loans');

        $this->translateDates($loans);

        return view('loans.index', [
            "loans" => $loans,
            "groups" => $groups
        ]);
    }
    private function translateDates($loans)
    {
        $daysTranslation = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $monthsTranslation = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        foreach ($loans as $loan) {
            $loanDate = Carbon::parse($loan->loan_date);
            $loanLength = Carbon::parse($loan->loan_length);

            $dateInd = $daysTranslation[$loanDate->isoFormat('dddd')];
            $monthDateInd = $monthsTranslation[$loanDate->isoFormat('MMMM')];
            $loan->translated_date = "{$dateInd}, tanggal {$loanDate->isoFormat('D')} {$monthDateInd} {$loanDate->isoFormat('Y')}, jam {$loanDate->format('H:i:s')}";

            $lengthInd = $daysTranslation[$loanLength->isoFormat('dddd')];
            $monthLengthInd = $monthsTranslation[$loanLength->isoFormat('MMMM')];
            $loan->translated_length = "{$lengthInd}, tanggal {$loanLength->isoFormat('D')} {$monthLengthInd} {$loanLength->isoFormat('Y')}, jam {$loanLength->format('H:i:s')}";
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userName = Auth::user()->user_name;
        $userPhone = Auth::user()->user_phone;
        $userID = Auth::user()->user_number_id;

        $groups = UserGroup::all();
        $assets = Asset::all();
        $categories = Category::all();
    
        return view('loans.createForm', compact('groups', 'assets' , 'categories', 'userName', 'userPhone', 'userID'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $this->validate($request, [
                'applicant_position' => 'required|string|max:50|min:8',
                'loan_desc' => 'required|string|max:200|min:10',
                'loan_date' => 'required',
                'loan_length' => 'required|after:loan_date',
                'itemNames.*' => 'required|string',
                'itemQuantities.*' => 'required|integer|min:1',
                'itemLoanPositions.*' => 'required|string',
                'itemLoanTypes.*' => 'required|string'
            ]);

            // Mendapatkan input barang-barang yang dipinjam
            $itemNames = $request->input('itemNames');
            $itemQuantities = $request->input('itemQuantities');
            $itemLoanPositions = $request->input('itemLoanPositions');
            $itemLoanTypes = $request->input('itemLoanTypes');

            $userName = Auth::user()->user_name;
            $userGroup = Auth::user()->group->group_name;
            $created_by = $userName . ' selaku ' . $userGroup;

            // Membuat deskripsi
            $description = 'Ingin dipakai oleh ' . $request->number_of_users . ' pengguna, guna ' . $request->loan_desc;

            // Membuat entri peminjaman untuk setiap barang dengan looping
            foreach($itemNames as $index => $itemName) {
                $loan = Loan::create([
                    'loan_name' => $itemLoanTypes[$index],
                    'applicant_name' => $request->applicant_name,
                    'applicant_position' => $request->applicant_position,
                    'applicant_phone' => $request->applicant_phone,
                    'applicant_number_id' => $request->applicant_number_id,
                    'loan_position' => $itemLoanPositions[$index],
                    'loan_asset_name' => $itemNames[$index],
                    'loan_asset_quantity' => $itemQuantities[$index],
                    'loan_desc' => $description,
                    'loan_date' => $request->loan_date,
                    'loan_length' => $request->loan_length,
                    'created_by' => $created_by,
                    'loan_note_status' => "|Menunggu Persetujuan| ",
                ]);
            }

            if ($loan) {
                return redirect()
                    ->route('loans.index')
                    ->with([
                        'success' => 'Data berhasil dibuat!'
                    ]);
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with([
                        'error' => 'Ada kesalahan'
                    ]);
            }
    }
    
    public function importLoanCsv(Request $request)
    {
        // Menyimpan file ke direktori sementara
        $csv = $request->file('csv_file');
        $csvPath = $csv->getRealPath();
    
        $loan_name = $request->loan_name;
    
        if (($handle = fopen($csvPath, "r")) !== FALSE) {
            // Menggunakan ';' sebagai delimiter
            $header = fgetcsv($handle, 1000, ";"); //header csv
            
            $success = true; // Menginisialisasi variabel untuk melacak status impor
            
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $applicant_name = $data[0];
                $applicant_position = $data[1];
                $applicant_phone = $data[2];
                $applicant_number_id = $data[3]; 
                $loan_asset_name = $data[4];
                $loan_asset_quantity = $data[5]; 
                $loan_desc = $data[6];
                $loan_position = $data[7]; 
                $loan_date = \Carbon\Carbon::parse($data[8]);
                $loan_length = \Carbon\Carbon::parse($data[9]);
    
                // Membuat dan menyimpan asset baru
                $loan = Loan::create([
                    'loan_name' => $loan_name,
                    'applicant_name' => $applicant_name,
                    'applicant_position' => $applicant_position,
                    'applicant_phone' => $applicant_phone,
                    'applicant_number_id' => $applicant_number_id,
                    'loan_position' => $loan_position,
                    'loan_asset_name' => $loan_asset_name,
                    'loan_asset_quantity' => $loan_asset_quantity,
                    'loan_desc' => $loan_desc,
                    'loan_date' => $loan_date,
                    'loan_length' => $loan_length,
                    
                    'created_by' => Auth::user()->user_name,
                    'loan_note_status' => "Data lama yang dimasukkan",
                    
                    'is_academic_approve'=>'Admin Akademik',
                    'is_coordinator_approve'=>'Admin Koordinator Aset',
                    'is_student_approve'=>'Admin Kemahasiswaan',
                    'is_wr_approve'=>'Admin WR',
                    'is_laboratory_approve'=>'Admin Lab',
                    
                    'is_full_approve'=>true,
                    'is_using'=>true,
                    'is_returned'=>true,
                ]);
    
                if (!$loan) {
                    $success = false;
                    break;
                }
            }
            fclose($handle);
    
            if ($success) {
                return redirect()
                    ->route('data.recap')
                    ->with('success', 'Data berhasil diimport dari CSV');
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Ada yang salah, silahkan coba lagi');
            }
        }
    
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Gagal membuka file csv');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        $groups = UserGroup::all();
        $user = Auth::user();
        $userName = $user->user_name;
        $userGroup = UserGroup::where('group_id', $user->group_id)->first();
        
        $userNumberId = $loan->applicant_number_id;

        // mencari jumlah aset yang dapat dipinjam 
        $assetParts = explode(' ', $loan->loan_asset_name); //pisahkan nama dan type
        $assetName = $assetParts[0];
        $assetType = implode(' ', array_slice($assetParts, 1));
        
        // mencoba semua kemungkinan kombinasi nama dan tipe
        for ($i = 1; $i < count($assetParts); $i++) {
            $assetName = implode(' ', array_slice($assetParts, 0, $i));
            $assetType = implode(' ', array_slice($assetParts, $i));
        
            $asset = Asset::where('asset_name', $assetName)
              ->where('asset_type', $assetType)
              ->first();
        
            if ($asset) {
                break;
            }
        }
        $assetQuantity = $asset->asset_quantity;
        // dd($asset, $assetQuantity);

        $assetParts = explode('guna ', $loan->loan_desc); //pisahkan nama dan type
        $loanDesc = $assetParts[1];

        return view('loans.editForm', compact('groups', 'loan', 'userName', 'userGroup', 'assetQuantity', 'userNumberId', 'loanDesc'));
    }



    public function destroy($loan_id)
    {
        // Logika penghapusan
        $loan = Loan::findOrFail($loan_id);

        $loanName = $loan->loan_name . ' ' . $loan->loan_asset_name;


        $loan->delete();

        if ($loan) {
            return redirect()
                ->route('loans.index')
                ->with([
                    'success' => "$loanName berhasil dibatalkan"
                ]);
        } else {
            return redirect()
                ->route('loans.index')
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $loan_id)
    {
        $this->validate($request, [
            'loan_desc' => 'required|string',
            'loan_position' => 'required',
            'loan_date' => 'required',
            'loan_length' => 'required|after:loan_date',
            'loan_asset_name' => 'required',
            'loan_asset_quantity' => 'required|integer|min:1',
        ]);         
 
        $loan = Loan::findOrFail($loan_id);

        if($loan -> is_coordinator_approve != null ||
            $loan -> is_academic_approve != null ||
            $loan -> is_wr_approve != null ||
            $loan -> is_laboratory != null){
                return redirect()
                ->back()
                ->with([
                    'error' => 'Maaf, penggajuan anda sudah diperiksa oleh admin. Pengeditan ini akan menjadi masalah bila diteruskan.'
                ]); 
            }
        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group->group_name;
        $updated_by = $userName . ' dari ' . $userGroup;

        // Membuat deskripsi
        $description = 'Ingin dipakai oleh ' . $request->number_of_users . ' pengguna, guna ' . $request->loan_desc;
 
        $loan->update([        
            'loan_name' => $request->loan_name,
            'loan_desc' => $description,
            'loan_position' => $request->loan_position,
            'loan_date' => $request->loan_date,
            'loan_length' => $request->loan_length,
            'loan_asset_name' => $request->loan_asset_name,
            'loan_asset_quantity' => $request->loan_asset_quantity,
            'updated_by' => $updated_by,
        ]);
 
        if ($loan) {
             return redirect()
                ->route('loans.index')
                ->with([
                    'success' => 'Data berhasil diperbarui'
                ]);
        } else {
             return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Ada yang salah, silahkan coba lagi'
                ]);
        }
    }

    public function approve(Request $request, $loan_id)
    {
        // Ambil semua tindakan persetujuan dari request
        $approvalActions = $request->input('approval_action', []);
        $loanNotes = $request->input('loan_note_status', []);

        foreach ($approvalActions as $actionLoanId => $action) {

            // Cari pinjaman berdasarkan ID
            $loan = Loan::findOrFail($actionLoanId);
            $group = Auth::user()->group_id;

            $on_group = DB::table('user_groups')->where('group_id', $group)->first();
            $sign_group_name = $on_group->group_name;
            $userName = Auth::user()->user_name;

            // Set the appropriate note for this loan
            $note = isset($loanNotes[$actionLoanId]) ? $loanNotes[$actionLoanId] : '';

            // Inisialisasi string catatan persetujuan
            
            $approvalNote = '';

            // Periksa apakah permintaan mengandung 'approval_action'
            if ($action == 'approve') {
                switch ($group) {
                    case 1:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Koordinator Aset') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_coordinator_approve = $userName;
                        if(empty($note)) {
                            $newStatus = "Disetujui $sign_group_name, ";
                        } else {
                            $newStatus = "Disetujui $sign_group_name (catatan: $note), ";
                        }
                        break;
                    case 2:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Akademik') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_academic_approve = $userName;
                        if(empty($note)) {
                            $newStatus = "Disetujui $sign_group_name, ";
                        } else {
                            $newStatus = "Disetujui $sign_group_name (catatan: $note), ";
                        }
                        break;
                    case 3:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Wakil Rektorr') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_wr_approve = $userName;
                        if(empty($note)) {
                            $newStatus = "Disetujui $sign_group_name, ";
                        } else {
                            $newStatus = "Disetujui $sign_group_name (catatan: $note), ";
                        }
                        break;
                    case 4:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Kemahasiswaan') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }               
                        $loan->is_student_approve = $userName;
                        if(empty($note)) {
                            $newStatus = "Disetujui $sign_group_name, ";
                        } else {
                            $newStatus = "Disetujui $sign_group_name (catatan: $note), ";
                        }
                        break;
                    case 10:
                        if ($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' || Str::contains($loan->loan_note_status, 'Kepala Lab Komputer]') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                            $loan->is_laboratory_approve = $userName;
                            if(empty($note)) {
                                $newStatus = "Disetujui $sign_group_name, ";
                            } else {
                                $newStatus = "Disetujui $sign_group_name (catatan: $note), ";
                            }
                        break;
                    
                    case 11:
                        if ($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' || Str::contains($loan->loan_note_statuse, '[Kepala Lab Ergonomi]') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                            $loan->is_laboratory_approve = $userName;
                            if(empty($note)) {
                                $newStatus = "Disetujui $sign_group_name, ";
                            } else {
                                $newStatus = "Disetujui $sign_group_name (catatan: $note), ";
                            }
                        break;
                        
                    default:
                        return redirect()
                            ->route('loans.index')
                            ->with([
                                'error' => 'Invalid group ID'
                            ]);
                }

                //menambahkan status
                $loan->loan_note_status = $loan->loan_note_status. $newStatus;

            } elseif ($action == 'reject') {
                switch ($group) {
                    case 1:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Koordinator Aset') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_coordinator_approve = null;
                        if(empty($note)){
                            return redirect()->back()->with('error', 'Bila menolak, harap tinggalkan catatan pada pengajuan yang ditolak');
                        }
                        $approvalNote = "$userName ($sign_group_name) menolak karena $note. ";
                        break;
                    case 2:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Akademik') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_academic_approve = null;
                        if(empty($note)){
                            return redirect()->back()->with('error', 'Bila menolak, harap tinggalkan catatan pada pengajuan yang ditolak');
                        }
                        $approvalNote = "$userName ($sign_group_name) menolak karena $note. ";
                        break;
                    case 3:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Wakil Rektor') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_wr_approve = null;
                        if(empty($note)){
                            return redirect()->back()->with('error', 'Bila menolak, harap tinggalkan catatan pada pengajuan yang ditolak');
                        }
                        $approvalNote = "$userName ($sign_group_name) menolak karena $note. ";
                        break;
                    case 4:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Kemahasiswaan') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_student_approve = null;
                        if(empty($note)){
                            return redirect()->back()->with('error', 'Bila menolak, harap tinggalkan catatan pada pengajuan yang ditolak');
                        }
                        $approvalNote = "$userName ($sign_group_name) menolak karena $note. ";
                        break;
                    case 5:
                        if($loan->is_full_approve == true || $loan->loan_note_status == 'Disetujui Penuh' || $loan->loan_note_status == 'Kadaluarsa' ||  Str::contains($loan->loan_note_status, 'Kepala Lab Komputer') || Str::contains($loan->loan_note_status, "$sign_group_name")){
                            return redirect()->back()->with('error', 'Pengajuan sudah dinilai oleh admin yang bersangkutan');
                        }
                        $loan->is_laboratory_approve = null;
                        if(empty($note)){
                            return redirect()->back()->with('error', 'Bila menolak, harap tinggalkan catatan pada pengajuan yang ditolak');
                        }
                        $approvalNote = "$userName ($sign_group_name) menolak karena $note. ";
                        break;
                    default:
                        return redirect()
                            ->route('loans.index')
                            ->with([
                                'error' => 'Invalid group ID'
                            ]);
                }
                $loan->is_reject = true;
            }

            $loanNote = $loan->loan_note_status;

            // Menggabungkan catatan persetujuan dengan pemisah tertentu

            if ($action == 'reject'){
                $loan->loan_note_status = $approvalNote;
            } else if ($action == 'approve' && !empty($approvalNote)) {
                $loan->loan_note_status .=  $approvalNote;
            } else {
                $loan->loan_note_status = $loanNote .' '. $approvalNote;
            }

            // Cek apakah semua persetujuan sudah diterima untuk mengubah status menjadi disetujui penuh
            if (($loan->loan_name == 'Peminjaman Barang' || $loan->loan_name == 'Peminjaman Alat') &&
                $loan->is_coordinator_approve != null &&
                $loan->is_student_approve != null &&
                $loan->is_wr_approve != null) {
                    $loan->loan_note_status = 'Peminjaman ' . $loan->loan_asset_name . ' Disetujui Penuh';
                    $loan->is_full_approve = true;
            } elseif ($loan->loan_name == 'Peminjaman Kendaraan' && 
                $loan->is_wr_approve != null && 
                $loan->is_coordinator_approve != null) {
                    $loan->loan_note_status = 'Peminjaman ' . $loan->loan_asset_name . ' Disetujui Penuh';
                    $loan->is_full_approve = true;
            } elseif ($loan->loan_name == 'Peminjaman Ruangan' &&
                $loan->is_academic_approve != null &&
                $loan->is_coordinator_approve != null &&
                $loan->is_student_approve != null) {
                    $loan->loan_note_status = 'Peminjaman ' . $loan->loan_asset_name . ' Disetujui Penuh';
                    $loan->is_full_approve = true;
            } elseif ($loan->loan_name == 'Peminjaman Laboratorium' &&
                    $loan->is_academic_approve != null &&
                    $loan->is_laboratory_approve != null &&
                    $loan->is_student_approve != null &&
                    $loan->is_wr_approve != null) { //Wakil Rektor hanya mengetahui karena surat ditunjukkan pada Wakil Rektor
                        $loan->loan_note_status = 'Peminjaman ' . $loan->loan_asset_name . ' Disetujui Penuh';
                        $loan->is_full_approve = true;
                    }
            $loan->save();
        }

        if ($loan) {
            return redirect()
                ->route('home.index')
                ->with([
                    'success' => 'Loans have been updated successfully'
                ]);
       } else {
            return redirect()
               ->back()
               ->withInput()
               ->with([
                   'error' => 'Ada yang salah, silahkan coba lagi'
               ]);
       }
        
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($loan_id)
    {
        // Mengambil data pinjaman dengan relasi returning dan using
        $loan = Loan::with(['returning', 'using'])->findOrFail($loan_id);
        if (!$loan) {
            return response()->view('errors.404', ['Data Dummy'], 404); 
        }

        $applicantName = $loan->applicant_name;
        $applicantNumberId = $loan->applicant_number_id;
        $loanDate = $loan->loan_date;
        $loanLength = $loan->loan_length;
        $createDate = $loan->created_at;
        $loanAssetName = $loan->loan_asset_name;
     
        // Filter data pinjaman lain berdasarkan kriteria
        $loanName = $loan->loan_name;
        
        //peminjaman utama
        $loans = Loan::where('applicant_number_id', $applicantNumberId)
            ->where('loan_name', $loanName)
            ->where('loan_asset_name', $loanAssetName)
            ->where('created_at', $createDate)
            ->where(function($query) {
                $query->where('loan_note_status', '!=', 'Kadaluarsa')
                    ->orWhere('is_returned', false);
            })->get();
     
        //peminjaman lain
        $on_request = Loan::where('loan_asset_name', $loanAssetName)
            ->where('applicant_number_id','NOT LIKE', $applicantNumberId)
            ->where(function($query) use ($loanDate, $loanLength) {
                $query->whereBetween('loan_date', [$loanDate, $loanLength])
                    ->orWhereBetween('loan_length', [$loanDate, $loanLength])
                    ->orWhere(function($query) use ($loanDate, $loanLength) {
                        $query->where('loan_date', '<=', $loanDate)
                            ->where('loan_length', '>=', $loanLength);
                    });
            })->get();

        // pecahkan nama aset yang dipinjam
        $parts = explode(' ', $loanAssetName);
        $assetName = $parts[0];
        $assetType = implode(' ', array_slice($parts, 1));

        // default
        $assetQuantity = 'Data aset';
        $onLoan = "tidak diketahui";
            
        // mencoba semua kemungkinan kombinasi nama dan tipe
        for ($i = 1; $i <= count($parts); $i++) {
            // Ambil bagian nama dan tipe dari aset
            $assetName = implode(' ', array_slice($parts, 0, $i));
            $assetType = implode(' ', array_slice($parts, $i));
        
            // Coba cari berdasarkan nama dan tipe
            $asset = Asset::where('asset_name', $assetName)
                ->where('asset_type', $assetType)
                ->first();
        
            // Jika ditemukan, berhenti mencari lebih lanjut
            if ($asset) {
                $onLoan = $asset->on_borrow ?? "tidak ada";
                $assetQuantity = 'jumlah tersedia ' . $asset->asset_quantity;
                break;
            }
        }
        
        // Jika tidak ditemukan, mencoba cari hanya berdasarkan nama
        if (!$asset) {
            $asset = Asset::where('asset_name', $loanAssetName)->first();
            
            if ($asset) {
                // Buat nama baru jika aset ditemukan
                $loanAssetName = 'Bila aset yang dimaksud adalah ' . $asset->asset_name . ' ' . ($asset->asset_type ?? 'Aset tidak ada');
                $onLoan = $asset->on_borrow ?? "tidak ada";
                $assetQuantity = 'jumlah tersedia ' . $asset->asset_quantity;
            } else {
                // Jika tidak ditemukan, buat nilai default
                $loanAssetName = 'Aset tidak ada';
                $onLoan = "tidak diketahui";
                $assetQuantity = "jumlah tidak diketahui";
            }
        }

        $groups = UserGroup::all();
        $sign_group_id = Auth::user()->group_id;
        $on_group = DB::table('user_groups')->where('group_id', $sign_group_id)->first();
        $sign_group_name = $on_group->group_name;

        if ($loan->is_using && !$loan->is_returned){
            $using = Using::where('document_number', $loan->loan_id)->firstOrFail();
            $using_evidence = $using->evidence;
            $using_text = $loan->using ? $loan->using->using_desc : $using->using_desc;

            return view('loans.view', compact('loan', 'loans', 'groups', 'applicantNumberId', 'applicantName', 'using_evidence', 'using_text'));
        }
     
        // Cek kembali apakah pinjaman sudah dikembalikan
        if ($loan->loan_note_status == 'Kadaluarsa') {
            
            $return_text = $loan->returning ? $loan->returning->evidence : null;

            $using = Using::where('document_number', $loan->loan_id)->firstOrFail();
            $using_evidence = $using->evidence;
            $using_text = $loan->using ? $loan->using->using_desc : $using->using_desc;

            $return = Returning::where('document_number', $loan->loan_id)->firstOrFail();
            $return_evidence = $return->evidence;
            $return_text = $loan->returning ? $loan->returning->return_desc: $return->return_desc;

            $aplicant_using_desc = explode(' ', $using_text);
            $using_quantity = implode(' ', array_slice($aplicant_using_desc, 4,8));

            return view('loans.oldLoanView', compact('loan', 'groups', 'applicantNumberId', 'applicantName', 'using_evidence', 'return_evidence', 'using_text', 'return_text', 'using', 'return'));
        }

        $this->translateDates($loans);

        return view('loans.view', compact('loan', 'loans', 'groups', 'applicantName', 'sign_group_name','applicantNumberId', 'on_request', 'loanAssetName', 'assetQuantity', 'onLoan'));
    }
        
    public function information()
    {
        $userName = Auth::user()->user_name;
        $userId = Auth::user()->user_id;

        $groups = LoanCategory::all();
        $groupData = $groups->map(function($group){
            return [
                'group_name' => $group->category_name,
                'group_desc' => $group->category_desc,
                'for_one_position' => $group->for_one_position,
                'for_one_name' => $group->for_one_name,
                'approvals' => array_map('trim', explode(',',  $group->approvals))
            ];
        });

        return view('loans.categories', compact('groupData', 'userName', 'userId'));
    }
}
