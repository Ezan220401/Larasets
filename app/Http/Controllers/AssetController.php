<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Loan;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        $categories = Category::all();

        $asset = Asset::query();
        
        $itemsPerPage = $request->input('items_per_page', 10); //default 10
    
        // Cek apakah ada input pencarian
        if ($request->has('search')) {
            $asset->where(function($query) use ($request) {
                $query->where('asset_name', 'like', '%' . $request->search . '%')
                      ->orWhere('asset_desc', 'like', '%' . $request->search . '%');
            });
        }
    
        // Menambahkan filter untuk kategori jika ada
        if ($request->has('categories') && $request->categories != 'all') {
            $asset->where('category_id', $request->categories);
        }
    
        $assets = $asset->with('category')->orderBy('asset_date_of_entry', 'desc')->paginate($itemsPerPage);
        return view('assets.index', compact('assets', 'categories'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = Auth::user()->group_id;
        if ($userId != 1) {
            abort(403, 'Forbidden');
        }

        $category = Category::all();
        $groups = UserGroup::all();

        $userName = Auth::user()->user_name;

        return view('assets.create', compact('category', 'userId', 'userName'));
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
            'asset_name' => 'required|string|min:3|max:70',
            'asset_code' => 'required',
            'asset_price' => 'required|integer|min:1000',
            'asset_type' => 'required|string',
            'asset_desc' => 'required|string|min:10|max:200',
            'maintenance_desc' => 'required|string|min:3|max:200',
            'asset_position' => 'required',
            'category_id' => 'required',
            'asset_date_of_entry' => 'required',
            'asset_quantity' => 'required|integer|min:1',
            'asset_image' => 'image|file|max:5110|mimes:jpeg,png,jpg',
        ]);
        
        $image = $request->file('asset_image');
        $imagePath = $image->store('asset_images', 'public');

        // Menentukan asset_code
        $category = Category::findOrFail($request->category_id);
        $prefix = $category->code; // misalkan category_id bernilai 1, maka ambil code pada baris 1, misal 170.80
        $suffix = '.01';

        // Memeriksa apakah kode asset sudah ada
        while (Asset::where('asset_code', $prefix . $suffix)->exists()) {
            $currentSuffixNumber = (int)substr($suffix, 1) + 1;
            $suffix = '.' . sprintf("%02d", $currentSuffixNumber);
        }

        $asset_code = $prefix . $suffix;

        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group->group_name;
        $created_by = $userName . ' selaku ' . $userGroup;
        
        $asset = Asset::create([
            'asset_name' => $request->asset_name,
            'asset_code' => $asset_code,
            'asset_position' => $request->asset_position,
            'receipt_number' => $request->receipt_number,
            'asset_desc' => $request->asset_desc,
            'maintenance_desc' => $request->maintenance_desc,
            'asset_price' => $request->asset_price,
            'asset_type' => $request->asset_type,
            'asset_date_of_entry' => $request->asset_date_of_entry,
            'category_id' => $request->category_id,
            'asset_quantity' => $request->asset_quantity,
            'asset_image' => $imagePath,
            'created_by' => $created_by
        ]);

        if ($asset) {
            return redirect()
                ->route('asset.index')
                ->with([
                    'success' => 'New asset has been created successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
        }
    }

   public function importAssetCsv(Request $request)
    {
        // Menyimpan file ke direktori sementara
        $csv = $request->file('csv_file');
        $csvPath = $csv->getRealPath();
    
        $category_id = $request->category_id;
    
        if (($handle = fopen($csvPath, "r")) !== FALSE) {
            // Menggunakan ';' sebagai delimiter
            $header = fgetcsv($handle, 1000, ";"); // Baris pertama csv diambil lebih dulu agar tidak ikut disimpan
            
            $success = true; // Menginisialisasi variabel untuk melacak status impor
            
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                // Ambil setiap kolom sesuai urutan yang diharapkan
                $asset_name = $data[0]; 
                $asset_type = $data[1]; 
                $asset_quantity = $data[2]; 
                $asset_desc = $data[3];
                $asset_position = $data[4];
                $receipt_number = $data[5]; 
                $asset_date_of_entry = \Carbon\Carbon::parse($data[6]);
                $asset_price = $data[7]; 
    
                // Menentukan asset_code
                $category = Category::findOrFail($category_id);
                $prefix = $category->code;
                $suffix = '.01';
    
                // Memeriksa apakah kode asset sudah ada
                while (Asset::where('asset_code', $prefix . $suffix)->exists()) {
                    $currentSuffixNumber = (int)substr($suffix, 1) + 1;
                    $suffix = '.' . sprintf("%02d", $currentSuffixNumber);
                }
    
                $asset_code = $prefix . $suffix;
    
                // Membuat dan menyimpan asset baru
                $asset = Asset::create([
                    'asset_name' => $asset_name,
                    'asset_code' => $asset_code,
                    'asset_position' => $asset_position,
                    'receipt_number' => $receipt_number,
                    'asset_desc' => $asset_desc,
                    'maintenance_desc' => '-',
                    'asset_price' => $asset_price,
                    'asset_type' => $asset_type,
                    'asset_date_of_entry' => $asset_date_of_entry,
                    'category_id' => $category_id,
                    'asset_quantity' => $asset_quantity,
                    'asset_image' => null,
                    'created_by' => Auth::user()->user_name
                ]);
    
                if (!$asset) {
                    $success = false;
                    break;
                }
            }
            fclose($handle);
    
            if ($success) {
                return redirect()
                    ->route('asset.index')
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($asset_id)
    {
        $asset = Asset::findOrFail($asset_id);
        $categories = Category::all();
        $qrcode = QrCode::size(100)->backgroundColor(23, 162, 184)->color(0, 0, 0)->margin(1)
                        ->generate($asset->asset_code);
        
        $months = Carbon::now()->subMonths(12);
        $loans = Loan::where('loan_date', '>=', $months)->orderBy('loan_date', 'asc')->get();

        $assetName = $asset->asset_name . ' ' . $asset->asset_type;
        $assetQuantity = $asset->asset_quantity;

        // Menemukan semua peminjaman aset dengan nama tertentu
        $assetOnLoan = $loans->where('loan_asset_name', $assetName);
        $conclusion = null;
        $weeklyLoans = collect(); // Inisialisasi variabel untuk menghindari error

        if ($assetOnLoan->isNotEmpty()) {
            // Mengelompokkan peminjaman per minggu dan menjumlahkan loan_asset_quantity di setiap minggu
            $weeklyLoans = $assetOnLoan->groupBy(function($loan){
                return Carbon::parse($loan->loan_date)->format('Y-m-d');
            })->map(function($groupedLoans) {
                return $groupedLoans->sum('loan_asset_quantity'); // Menjumlahkan jumlah aset yang dipinjam per minggu
            })->sortKeys();

            // Pengurutan
            $weeklyLoans = $weeklyLoans->sortKeys();

            // Kalkulasi SMA dengan periode 3 minggu terakhir
            $smaPeriod = 3;
            $last3Weeks = $weeklyLoans->slice(-$smaPeriod); // Mengambil 3 minggu terakhir
            $sma = $this->calculateSMA($last3Weeks, $smaPeriod);
            $forecastingAsset = end($sma);

            // Kesimpulan
            if ($forecastingAsset < $assetQuantity) {
                $conclusion = 'Saat ini jumlah aset tampak baik';
            } else {
                $conclusion = 'Mungkin lebih baik anda menambah jumlah ' . $assetName;
            }
        }

        // Kirimkan data ke view
        return view('assets.view', compact('asset', 'qrcode', 'categories', 'conclusion', 'weeklyLoans'));
    }


    private function calculateSMA(Collection $data, int $smaPeriod){
        $sma = [];
        if(count($data) >= $smaPeriod) {
            $window = $data->take($smaPeriod);
            $average = $window->sum() / $smaPeriod; // Rumus Simple Moving Average
            $sma[] = $average;
        }

        return $sma;
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($asset_id)
    {
        $userId = Auth::user()->group_id;
        if ($userId != 1) {
            abort(403, 'Forbidden');
        }
        $category = Category::all();
        $groups = UserGroup::all();
        $asset = Asset::findOrFail($asset_id);
        
        // Kirimkan data ke view
        return view('assets.edit', compact('asset', 'category', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $asset_id)
    {
        
        $this->validate($request, [
            'asset_name' => 'required|string|max:200',
            'asset_type' => 'required|string',
            'asset_desc' => 'required|string',
            'maintenance_desc' => 'required|string',
            'asset_position' => 'required',
            'category_id' => 'required',
            'asset_quantity' => 'required|integer|min:1',
            'asset_image' => 'image|file|max:5110|mimes:jpeg,png,jpg',
        ]);
        
        $asset = Asset::findOrFail($asset_id);

        if($asset->category_id != $request->category_id){
           // menentukan asset_code baru
            $category = Category::findOrFail($request->category_id);
            $prefix = $category->code; // misalkan category_id bernilai 1, maka ambil code pada baris 1, misal 170.80
            $suffix = '.01';

            // memeriksa apakah kode asset sudah ada
            while (Asset::where('asset_code', $prefix . $suffix)->exists()) {
                $currentSuffixNumber = (int)substr($suffix, 1) + 1;
                $suffix = '.' . sprintf("%02d", $currentSuffixNumber);
            }

            $asset_code = $prefix . $suffix;
            $asset->update([
                'asset_code' => $asset_code,
            ]);
        }

        $image = $request->file('asset_image');

        if($image != null){
            $imagePath = $image->store('asset_images', 'public');
            $asset->update([
                'asset_image' => $imagePath,
            ]);
        }  

        $asset->update([
            'asset_name' => $request->asset_name,
            'asset_position' => $request->asset_position,
            'asset_desc' => $request->asset_desc,
            'maintenance_desc' => $request->maintenance_desc,
            'receipt_number' => $request->receipt_number,
            'asset_type' => $request->asset_type,
            'updated_by' => $request->updated_by,
            'asset_date_of_entry' => $request->asset_date_of_entry,
            'category_id' => $request->category_id,
            'asset_quantity' => $request->asset_quantity,
        ]);

        if ($asset) {
            return redirect()
                ->route('asset.index')
                ->with([
                    'success' => 'Aset berhasil diperbarui'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($asset_id)
    {
        $asset = Asset::findOrFail($asset_id);

        if($asset->user_photo){
            Storage::delete($asset->user_photo);
        }

        $assetName = $asset->asset_name . ' tipe ' . $asset->asset_type;

        $asset->delete();

        if ($asset) {
            return redirect()
                ->route('asset.index')
                ->with([
                    'success' => "Data $assetName berhasil dihapus"
                ]);
        } else {
            return redirect()
                ->route('asset.index')
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }

    public function exportLabel($asset_id){
        // $asset_id = $request->query('asset_id');
        $asset = Asset::findOrFail($asset_id);
        $categories = Category::all();
        $qrcode = QrCode::size(100)->backgroundColor(23, 162, 184)->color(0, 0, 0)->margin(1)
                    ->generate('http://localhost:8000/asset/' . $asset_id);

        $asset_code_prefix = substr($asset->asset_code, 0, 2);
        $asset_code = $asset->asset_code;
        $codes = [];
        for ($i = 1; $i <= $asset->asset_quantity; $i++) {
            $codes[] = $asset_code . str_pad($i, strlen((string)$asset->asset_quantity), '0', STR_PAD_LEFT);
        }
        return view('template.labeltemplate', compact('asset', 'qrcode', 'codes'));

        // dd($asset_id);
    }

    public function information()
    {

        $groups = Category::all();
        $assets = Asset::paginate(70);

        return view('assets.categories', compact('groups', 'assets'));
    }

    public function category_store(Request $request)
    {
        $this->validate( $request, [
            'category_name' => 'required|string',
            'category_desc' => 'required|string',
            'category_code' => 'required'
        ]);

        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group_id;

        // Cek apakah user memiliki izin (misalnya hanya group_id 1 yang bisa mengupdate)
        if ($userGroup != 1) {
            abort(403, 'Forbidden');
        }

        // Cek apakah nama kategori sudah ada, kecuali untuk kategori yang sedang diperbarui
        $check_category_name = Category::where('category_name', $request->category_name)
            ->first();
            
        if ($check_category_name) {
            return redirect()->back()->withInput()->with([
                'error' => 'Nama kategori sudah ada'
            ]);
        }

        // Cek apakah kode kategori sudah ada, kecuali untuk kategori yang sedang diperbarui
        $check_category_code = Category::where('code', $request->category_code)
            ->first();
            
        if ($check_category_code) {
            return redirect()->back()->withInput()->with([
                'error' => 'ID kategori sudah ada'
            ]);
        }

        $created_by = $userName . ' selaku ' . $userGroup;

        $asset_category = Category::create([
            'category_name' => $request->category_name,
            'category_desc' => $request->category_desc,
            'code' => $request->category_code,
            'created_by' => $created_by
        ]);

        if ($asset_category) {
            return redirect()
                ->route('asset.information')
                ->with([
                    'success' => 'Kategori Berhasil Ditambahkan'
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }
    
    public function category_edit($category_id)
    {
        $category = Category::findOrFail($category_id);
        return view('categories.updateAssetCategory', compact('category'));
    }

    public function category_update(Request $request, $category_id)
    {
        $this->validate($request, [
            'category_name' => 'required|string',
            'category_desc' => 'required|string',
            'category_code' => 'required|string'
        ]);

        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group_id;

        // Cek apakah user memiliki izin (misalnya hanya group_id 1 yang bisa mengupdate)
        if ($userGroup != 1) {
            abort(403, 'Forbidden');
        }

        // Cek apakah nama kategori sudah ada, kecuali untuk kategori yang sedang diperbarui
        $check_category_name = Category::where('category_name', $request->category_name)
            ->where('category_id', '!=', $category_id)
            ->first();
            
        if ($check_category_name) {
            return redirect()->back()->withInput()->with([
                'error' => 'Nama kategori sudah ada'
            ]);
        }

        // Cek apakah kode kategori sudah ada, kecuali untuk kategori yang sedang diperbarui
        $check_category_code = Category::where('code', $request->category_code)
            ->where('category_id', '!=', $category_id)
            ->first();
            
        if ($check_category_code) {
            return redirect()->back()->withInput()->with([
                'error' => 'ID kategori sudah ada'
            ]);
        }

        $updated_by = $userName . ' selaku ' . $userGroup;

        // Temukan kategori yang akan diperbarui
        $asset_category = Category::findOrFail($category_id);

        // Perbarui data kategori
        $asset_category->update([
            'category_name' => $request->category_name,
            'category_desc' => $request->category_desc,
            'code' => $request->category_code,
            'updated_by' => $updated_by
        ]);

        if ($asset_category) {
            return redirect()
                ->route('asset.information')
                ->with([
                    'success' => 'Kategori Berhasil Diperbarui'
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }



    public function category_destroy($category_id)
    {
        $asset = Category::findOrFail($category_id);
        $asset->delete();

        if ($asset) {
            return redirect()
                ->route('asset.information')
                ->with([
                    'success' => 'Category has been deleted successfully'
                ]);
        } else {
            return redirect()
                ->route('asset.information')
                ->with([
                    'error' => 'Some problem has occurred, please try again'
                ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $asset_id = $request->asset_id;
        $status = $request->status;

        $record = Asset::find($asset_id);
        if ($record) {
            $record->maintenance = $status;
            $record->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
