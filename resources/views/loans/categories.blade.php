@extends('layouts.master')

@section('titlePage', 'Informasi Peminjaman')
@section('title', 'Informasi Peminjaman')

@section('content')
<div class="container text-center">
    <img src="{{ asset('img/alur.png') }}" alt="Alur Peminjaman" style="width: 90%; margin-bottom: 20px;">
    <div class="row ">
        <div>
            <h4 class="bg-secondary p-2 text-white text-left"><b>Perlu diingat! Sebelum mengajukan peminjaman:</b></h4>
            <ul>
                <li class="text-left"><b>Pengajuan minimal </b> 3 hari sebelum pemakaian.</li>
                <li class="text-left"><b>Masa peminjaman </b> maksimal 7 hari pemakaian.</li>
                <li class="text-left"><b>Setiap aset </b> memiliki harga satuan, bila terjadi sesuatu hal yang tidak diinginkan sehingga mengakibatkan ganti rugi dari pihak peminjam dapat diselesaikan dengan baik-baik dan dengan bukti yang dapat dikumpulkan.</li>
                <li class="text-left"><b>Setiap pengaju atau peminjam wajib memberikan informasi terkini</b>, segala macam bentuk kerusakan, kehilangan atau hal lain yang tidak diinginkan menjadi tanggung jawab peminjam setelah pemakaian disetujui.</li>
            </ul>
        </div>
        <div class="card mb-4">
            @forelse($groupData as $group)
                <div class="bg-light card-body text-left text-black">
                    <!-- Nama kategori -->
                    <h4 class="bg-primary p-2 text-white text-center">{{ $group['group_name'] }}</h4>
                    
                    <!-- Deskripsi -->
                    <h5>Deskripsi</h5>
                    <p>{{ $group['group_desc'] }}</p>
                    
                    <!-- Ketentuan -->
                    <h5>Persetujuan yang harus didapatkan</h5>
                        <ul>
                            @foreach ($group['approvals'] as $approval)
                                <li>{{ $approval }}</li>
                            @endforeach
                        </ul>                     
                    <h5>Penerima Surat Pengajuan</h5>
                    <p>{{ $group['for_one_position'] }}, {{ $group['for_one_name'] }}</p>
                </div>
                <hr>
            @empty
                <h4 class="text-center">Ada masalah saat memuat data</h4>
            @endforelse
        </div>
    </div>
</div>
        
@endsection