<?php

namespace App\Http\Controllers;

use App\Models\EventSchedule;
use App\Models\Loan;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userNumberId = Auth::user()->user_number_id;
        $group_id = Auth::user()->group_id;

        // Ambil data grup user
        $group = DB::table('user_groups')->where('group_id', $group_id)->first();
        $group_name = $group ? $group->group_name : 'Nama Grup Tidak Ditemukan';

        if (!$group) {
            return redirect()->route('action_logout');
        }

        $loanQuery = Loan::query();
        // Filter pencarian
        if ($request->has('search')) {
            $loanQuery->where(function($query) use ($request) {
                $query->where('loan_name', 'like', '%' . $request->search . '%')
                    ->orWhere('loan_asset_name', 'like', '%' . $request->search . '%')
                    ->orWhere('applicant_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori
        if ($request->has('categories') && $request->categories != 'all') {
            $loanQuery->where('loan_name', 'like', '%' . $request->categories . '%');
        }

        // Set awal query loan berdasarkan user_number_id
        $loan_user = (clone $loanQuery)->where('applicant_number_id', $userNumberId)->paginate(20);

        // Ambil data loans untuk user
        $loans = $loanQuery->paginate(20);

        // Ambil data sesuai pekerjaan admin
        $loanConditions = [
            1 => ['Peminjaman Barang', 'Peminjaman Alat', 'Peminjaman Kendaraan', 'Peminjaman Ruangan'],
            2 => ['Peminjaman Laboratorium', 'Peminjaman Ruangan'],
            3 => ['Peminjaman Kendaraan', 'Peminjaman Ruangan', 'Peminjaman Laboratorium', 'Peminjaman Barang', 'Peminjaman Alat'],
            4 => ['Peminjaman Barang', 'Peminjaman Alat', 'Peminjaman Ruangan', 'Peminjaman Laboratorium'],
            10 => ['Peminjaman Laboratorium'],
            11 => ['Peminjaman Laboratorium']
        ];
    
        $campus_loan_status = $this->filterLoans($loanQuery, $group_name, $loanConditions[$group_id] ?? []);
        $student_loan_status = $this->filterLoans($loanQuery, $group_name, $loanConditions[$group_id] ?? [], true);

        // Dapatkan pinjaman yang jatuh pada hari ini
        $startOfDay = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();
        $loans_today = Loan::where('loan_date', '<=', $endOfDay)
                        ->where('loan_length', '>=', $startOfDay)
                        ->get();

        // Translasi tanggal
        $this->translateDates($loan_user);
        $this->translateDates($campus_loan_status);
        $this->translateDates($student_loan_status);

        return view('dashboard.index', compact('campus_loan_status', 'student_loan_status', 'group_name', 'loan_user', 'loans_today'));
    }

    private function filterLoans($loanQuery, $group_name, $loanNames, $isStudent = false)
    {
        $group_id = Auth::user()->group_id;

        return (clone $loanQuery)
            ->where('created_by', $isStudent ? 'LIKE' : 'NOT LIKE', '%selaku Mahasiswa%')
            ->where('loan_note_status', 'NOT LIKE', '%' . $group_name . '%')
            ->where('is_using', false)
            ->where('is_returned', false)
            ->where('is_reject', false)
            ->where('is_full_approve', false)
            ->when($loanNames, function($query) use ($loanNames, $group_id) {
                $query->where(function($q) use ($loanNames, $group_id) {
                    foreach ($loanNames as $name) {
                        $q->orWhere('loan_name', 'LIKE', '%' . $name . '%');
                    }
                    if ($group_id == 10) {
                        $q->where('loan_asset_name', 'LIKE', '%Lab Komputer%');
                    } elseif ($group_id == 11) {
                        $q->where('loan_asset_name', 'LIKE', '%Lab Ergonomi%');
                    }
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(request()->input('items_per_page', 10), ['*'], $isStudent ? 'student_loan_status' : 'campus_loan_status');
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

    
    public function destroy($loan_id)
    {
        // Logika penghapusan
        $loan = Loan::findOrFail($loan_id);
        $loan->delete();

        // Redirect atau response setelah penghapusan
        return redirect()->route('loan.index')->with('success', 'Peminjaman dibatalkan');
    } 

    public function getEvents()
    {
        $schedules = Loan::where('is_full_approve', true)->get();
        $events = [];
        $color = '';
        foreach ($schedules as $schedule) {
            if (strpos($schedule->created_by, 'Mahasiswa') !== false) {
                $color = 'blue';
            } else {
                $color = 'orange';
            }
            $events[] = [
                'id' => $schedule->loan_id,
                'title' => 'Peminjaman '. $schedule->loan_asset_quantity . ' ' . $schedule->loan_asset_name . ' untuk ' . $schedule->loan_desc,
                'start' => Carbon::parse($schedule->loan_date)->toIso8601String(),
                'end' => Carbon::parse($schedule->loan_length)->toIso8601String(),
                'color' => $color
            ];
        }
        return response()->json($events);
    }
}