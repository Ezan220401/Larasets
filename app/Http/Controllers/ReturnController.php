<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\Returning;
use App\Models\UserGroup;
use App\Models\Using;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function returning($loan_id)
    {
        $loan = Loan::findOrFail($loan_id);
        $groups = UserGroup::all();
        $user = Auth::user();

        $loan = Loan::findOrFail($loan_id);
        $number_document = $loan->loan_id;
        
        $userName = $user->user_name;
        $userGroup = UserGroup::where('group_id', $user->group_id)->first();

        return view('loans.returnForm', compact('groups', 'loan', 'userName', 'userGroup', 'number_document'));
    }

    public function return_evidence(Request $request, $loan_id)
    {
        
        // validasi
        $this->validate($request, [
            'person_name' => 'required|string',
            'witness_name' => 'required|string',
            'witness_group' => 'required',
            'asset_name' => 'required|string',
            'asset_quantity' => 'required|integer',
            'return_desc' => 'required|string|min:10',
            'return_date' => 'required|date',
            'evidence_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // simpan gambar
        $image = $request->file('evidence_image');
        $imagePath = $image->store('evidence_images', 'public');
        //membuat deskripsi

        $loan = Loan::where('loan_id', $request->document_number)->firstOrFail();
        $on_using = Using::where('document_number', $request->document_number)->firstOrFail();
        $description = '';
        // mengonversi tanggal
        $loanLength = Carbon::parse($loan->loan_length);
        $returnLength = Carbon::parse($request->return_date);

        // jumlah
        $assetQuantity = $loan->loan_asset_quantity;
        $returnQuantity = $request->asset_quantity;

        // toleransi keterlambatan
        $returnDiff = $loanLength->diffInMinutes($returnLength, false);
        $toleranceMinute = 60;

        $on_using_quantity = $on_using->asset_quantity;

        // membandingkan tanggal dan jumlah
        $quantity_desc = '';
        $time_desc = '';

        if ($returnDiff > $toleranceMinute) {
            $time_desc = 'Pengembalian terlambat';
        } elseif ($returnLength->lessThan($loanLength)) {
            $time_desc = 'Pengembalian lebih awal';
        } else {
            $time_desc = 'Pengembalian tepat waktu';
        }

        if ($assetQuantity < $returnQuantity || $on_using_quantity < $returnQuantity){
            $quantity_desc = 'mengembalikan ' . $returnQuantity . ' (Kesepakatannya ' . $assetQuantity . ',jumlah yang dikembalikan lebih banyak). ';
        } elseif ($on_using_quantity >  $returnQuantity || $assetQuantity > $returnQuantity){
            $quantity_desc = 'mengembalikan ' . $returnQuantity . ' (Kesepakatannya ' . $assetQuantity . ',jumlah yang dikembalikan kurang). ';
        } else {
            $quantity_desc = 'mengembalikan ' . $returnQuantity . ' (Kesepakatannya ' . $assetQuantity . ',jumlah yang dikembalikan tepat). ';
        }

        $description = $request->return_desc .'. [ Keterangan lain: ' . $time_desc . ' dan ' . $quantity_desc .']';

        $person_group = Auth::user()->group_id;


        // membuat data baru pada tabel pengembalian
        $return = Returning::create([
            'person_name' => $request->person_name,
            'person_position' => $person_group,
            'witness_name' => $request->witness_name,
            'witness_position' => $request->witness_group,
            'document_number' => $request->document_number,
            'asset_name' => $request->asset_name,
            'asset_quantity' => $request->asset_quantity,
            'return_desc' => $description,
            'return_date' => $request->return_date,
            'created_by' => $request->updated_by,
            'evidence' => $imagePath
        ]);

        $return_id = $return->return_id;
        
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
            $asset->on_borrow -= $request->asset_quantity;
            $asset->save();
        }
        
        // mengedit status peminjaman pada tabel peminjaman 
        $loan = Loan::findOrFail($loan_id);
        $loan->is_returned = true;
        $loan->return_id = $return_id;
        $loan->loan_note_status = 'Kadaluarsa';
        $loan->save();

        // kembali ke laman awal
        if ($return) {
            return redirect()
                ->route('loans.index')
                ->with([
                    'success' => 'Data berhasil diperbarui, terimakasih telah mengembalikan aset kami'
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