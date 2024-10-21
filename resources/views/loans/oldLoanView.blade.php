@extends('layouts.master')
@section('titlePage', 'Informasi Peminjaman')
@section('title', 'Informasi Peminjaman')
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif    

    @php
    use Carbon\Carbon;

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

            $loanDate = Carbon::parse($loan->loan_date);
            $loanLength = Carbon::parse($loan->loan_length);
        
            $dateInd = $daysTranslation[$loanDate->isoFormat('dddd')];
            $monthDateInd = $monthsTranslation[$loanDate->isoFormat('MMMM')];
            $loan->translated_date = "{$dateInd}, tanggal {$loanDate->isoFormat('D')} {$monthDateInd} {$loanDate->isoFormat('Y')}, jam {$loanDate->format('H:i:s')}";

            $lengthInd = $daysTranslation[$loanLength->isoFormat('dddd')];
            $monthLengthInd = $monthsTranslation[$loanLength->isoFormat('MMMM')];
            $loan->translated_length = "{$lengthInd}, tanggal {$loanLength->isoFormat('D')} {$monthLengthInd} {$loanLength->isoFormat('Y')}, jam {$loanLength->format('H:i:s')}";

    @endphp

    <!-- Deskripsi -->
        <p class="blockquote">Peminjaman<span class="text-white bg-info rounded pl-3 pr-3"> {{ $loan->loan_asset_quantity }} buah {{ $loan->loan_asset_name }} </span> untuk keperluan {{ $loan->loan_desc }}. 
        Mulai dari <mark>{{ $loan->translated_date }}</mark> sampai <mark>{{ $loan->translated_length }}</mark>.</p>
        <hr>
        <h5 class="bg-success rounded p-3">Peminjaman ini sudah selesai</h5>
        <div class="row">
            <!-- Pemakaian -->
            <div class="col-md-6">
                <h5>Kondisi Sebelum Dipakai</h5>
                @if($using_evidence)
                    <img class="img-fluid" style="min-height: 200px; max-height: 200px;"  src="{{ asset('/storage/' . $using_evidence) }}" alt="Bukti pengambilan {{ $loan->loan_asset_name }}">
                @else
                    <p>Gambar tidak tersedia.</p>
                @endif
                    <p><b>Diambil pada: </b>
                        <i class="fa-solid fa-calendar"></i> {{ \Carbon\Carbon::parse($using->using_date)->format('d-m-Y') }} 
                        <i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($using->using_date)->format('H:i') }}
                    <p><b>Pengambil: </b>{{ $using->person_name }}</p>
                    <p><b>Saksi: </b>{{ $using->witness_name }}</p>
                    <p><b>Keterangan: </b>{{ $using_text }}</p>
            </div>

            <!-- Pengembalian -->
            <div class="col-md-6">
                <h5>Kondisi Setelah Dipakai</h5>
                @if($return_evidence)
                    <img class="img-fluid" style="min-height: 200px; max-height: 200px;" src="{{ asset('/storage/'. $return_evidence) }}" alt="Bukti pengembalian {{ $loan->loan_asset_name }}">
                @else
                    <p>Gambar tidak tersedia.</p>
                @endif
                <p><b>Dikembalikan pada: </b>
                    <i class="fa-solid fa-calendar"></i> {{ \Carbon\Carbon::parse($using->return_date)->format('d-m-Y') }} 
                    <i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($using->return_date)->format('H:i') }}
                <p><b>Pengembali: </b>{{ $return->person_name }}</p>
                <p><b>Saksi: </b>{{ $return->witness_name }}</p>
                <p><b>Keterangan: </b>{{ $return_text }}</p>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
@endsection
