<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\Returning;
use App\Models\UserGroup;
use App\Models\Using;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsingController extends Controller
{
    public function using($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        $groups = UserGroup::all();
        $user = Auth::user();
        $number_document = $loan->loan_id;

        $userName = Auth::user()->user_name;
        // $userGroup = Auth::user()->group_id;
        $group = UserGroup::where('group_id', $user->group_id)->first();
        $userGroup = $group->group_name;

        // cek status
        if ($loan->is_full_approve != true) {
            return redirect()->back()->withErrors('Pengajuan anda belum sepenuhnya dikonfirmasi');
        }

        $loanDate = Carbon::parse($loan->loan_date)->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');

        return view('loans.usingForm', compact('groups', 'loan', 'userName', 'userGroup', 'number_document'));
    }

    public function using_evidence(Request $request, $loan_id)
    {

        // Validasi input
        $this->validate($request, [
            'person_name' => 'required|string',
            'witness_name' => 'required|string',
            'witness_group' => 'required|integer',
            'asset_quantity' => 'required|integer',
            'using_desc' => 'required|string|min:10',
            'using_date' => 'required|date',
            'evidence_image' => 'required|image|file|max:5110|mimes:jpeg,png,jpg'
        ]);

        // menyimpan gambar
        if ($request->hasFile('evidence_image')) {
            $image = $request->file('evidence_image');
            $imagePath = $image->store('evidence_images', 'public');
        } else {
            return back()->with('error', 'No image file found');
        }
        
        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group_id;
        $created_by = $userName . ' selaku ' . $userGroup;

        //membuat deskripsi
        $loan = Loan::findOrFail($loan_id);
        $description = '';

        // mengonversi tanggal
        $loanDate = Carbon::parse($loan->loan_date);
        $usingDate = Carbon::parse($request->using_date);

        // toleransi keterlambatan
        $usingDiff = $loanDate->diffInMinutes($usingDate, false);
        $toleranceMinute = 30;

        // jumlah
        $assetQuantity = $loan->loan_asset_quantity;
        $usingQuantity = $request->asset_quantity;

        // membandingkan tanggal dan jumlah

        $quantity_desc = '';
        $time_desc = '';

        if ($usingDiff > $toleranceMinute) {
            $time_desc = 'Diambil terlambat';
        } elseif ($usingDate->lessThan($loanDate)) {
            $time_desc = 'Diambil lebih awal';
        } else {
            $time_desc = 'Diambil tepat waktu';
        }

        if ($assetQuantity < $usingQuantity) {
            $quantity_desc= 'mengambil ' . $usingQuantity . ' (Kesepakatannya ' . $assetQuantity . ', mengambil lebih banyak daripada yang diajukan). ';
        } elseif ($assetQuantity > $usingQuantity) {
            $quantity_desc= 'mengambil ' . $usingQuantity . ' (Kesepakatannya ' . $assetQuantity . ', mengambil lebih sedikit daripada yang diajukan). ';
        } else {            
            $quantity_desc= 'mengambil ' . $usingQuantity . ' (Kesepakatannya ' . $assetQuantity . ', mengambil sesuai kesepakatan). ';
        }

        $description =  $request->using_desc .'. [ Keterangan lain: ' . $time_desc . ' dan ' . $quantity_desc .']';

        $person_group = Auth::user()->group_id;
        // Membuat data baru pada tabel Using
        $using = Using::create([
            'person_name' => $request->person_name,
            'person_position' => $person_group,
            'witness_name' => $request->witness_name,
            'witness_position' => $request->witness_group,
            'document_number' => $request->document_number,
            'asset_name' => $request->asset_name,
            'asset_quantity' => $request->asset_quantity,
            'using_desc' => $description,
            'using_date' => $request->using_date,
            'created_by' => $created_by,
            'evidence' => $imagePath
        ]);

        $using_id = $using->using_id;

        // mengedit tabel aset dan peminjaman
        $assetParts = explode(' ', $request->asset_name); //pisahkan nama dan type
        $assetName = $assetParts[0];
        $assetType = implode(' ', array_slice($assetParts, 1));
        
        //mencoba berbagai kombinasi untuk mendapat nama aset
        for ($i = 1; $i < count($assetParts); $i++){
            $assetName = implode(' ', array_slice($assetParts, 0, $i));
            $assetType = implode(' ', array_slice($assetParts, $i));

            $asset = Asset::where('asset_name', $assetName)
                ->where('asset_type', $assetType)->first();

            if ($asset) {
                break;
            }
        }

        if($asset){
            $asset->on_borrow += $request->asset_quantity;
            $asset->save();
        }

        $loan->is_using = true;
        $loan->using_id = $using_id;
        $loan->loan_note_status = 'Sedang Menggunakan';
        $loan->save();

        // Kembali ke laman awal dengan pesan sukses atau gagal
        if ($using) {
            return redirect()
                ->route('loans.index')
                ->with([
                    'success' => 'Data Berhasil diperbarui, harap jaga aset dengan baik.'
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
}
