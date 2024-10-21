@extends('layouts.letterMaster')

@section('title', 'Submission | Laboratorium')
<style>
    table{
        border-left: none;
        border-right: none;
        border-top: none;
        border-bottom: 2px solid black;
    }
</style>
@section('content')
<div>
    <!-- Kop surat -->
        <header class="mb-4"> 
            <table class="table table-borderless">
                <tr>
                    <td style="width: 150px;">
                        <img style="width: 120px;" src="{{asset('img/logo_utb.png')}}" alt="">
                    </td>
                    <td width="400px">
                        <p class="mb-0 text-center"><b>UNIVERSITAS TEKNOLOGI BANDUNG <br> 
                            Jl. Soekarno Hatta No. 378 Bandung 40235 <br> 
                            (022)5224000</b></p>
                        <hr>
                        <h1 class="text-center" style="font-size: 14pt;">PEMINJAMAN LABORATORIUM</h1>
                    </td>
                    <td style="text-align: left; width: 200px;">
                        <p><b>No. Dokumen:</b> {{$loans->loan_id}}</p>
                        <p><b>No. Revisi:</b> {{$loans->loan_id}}{{$updatedAt}}</p>
                        <p><b>Kadaluarsa:</b> {{$loanLength}}</p>
                    </td>
                </tr>
            </table>
        </header>


        <main>
            <!-- Main -->
            @if($assetFiltered)
                <div id="opening">
                    <p class="text-justify">Kpd yang terhormat, Kepala {{ $assetFiltered->loan_asset_name }},
                        <br>Di Tempat
                    </p>
                    <p class="text-justify">Yang bertanda tangan dibawah ini:
                        <p class="pl-4">Nama   : <label>{{$assetFiltered->applicant_name}}</label> </p>
                        <p class="pl-4">Jabatan: <label>{{$assetFiltered->applicant_position}}</label> </p>
                    </p>
                    <p class="text-justify" style="margin-top: 100px;">Bermaksud untuk mengajukan permohonan penggunaan ruangan <u>{{ $assetFiltered->loan_asset_name }}</u>, milik Universitas Teknologi Bandung.</p>
                </div>

                <div class="content mb-4">
                <p class="pl-4">Lama peminjaman: <label>{{ $assetFiltered->loan_date }} sampai dengan {{ $assetFiltered->loan_length }}</label></p>
                <p class="pl-4">Untuk keperluan: <label>{{ $assetFiltered->loan_desc }}</label></p>
                </div>

                <p class="text-justify">Demikian daftar permohonan penggunaan ruangan <u>{{ $assetFiltered->loan_name }}</u> yang saya ajukan, atas kerjasamanya saya ucapkan terimakasih</p>

                <!-- Approval -->
                <div id="approval" class="approval-grid">
                    <div class="approval-item">
                        <h5>Akademik</h5>
                        @if($assetFiltered->is_academic_approve != null)
                            <img style="width: 150px;" class="alert alert-primary text-white p-1" src="{{asset('img/mengetahui.png')}}" alt="Mengetahui">
                            <div id="sign">
                                <p>{{$assetFiltered->is_academic_approve}} </p></div>
                        @elseif ($assetFiltered->is_reject != null)
                            <img class="alert alert-danger text-white p-1" style="width: 150px;" src="{{asset('img/menolak.png')}}" alt="Menolak">
                        @else
                            <p class="bg-secondary text-white p-1">Menunggu</p>
                        @endif
                    </div>
                    <div class="approval-item">
                        <h5>Kemahasiswaan</h5>
                        @if($assetFiltered->is_student_approve != null)
                            <img class="alert alert-primary text-white p-1" style="width: 150px;" src="{{asset('img/mengetahui.png')}}" alt="Mengetahui">
                            <div id="sign">
                                <p>{{$assetFiltered->is_student_approve }}</p></div>
                        @elseif ($assetFiltered->is_reject != null)
                            <img class="alert alert-danger text-white p-1" style="width: 150px;" src="{{asset('img/menolak.png')}}" alt="Menolak">
                        @else 
                            <p class="bg-secondary text-white p-1">Menunggu</p>
                        @endif
                    </div>
                    <div class="approval-item">
                        <h5>Pemohon</h5>
                        <img style="width: 150px;" class="alert alert-primary text-white p-1" src="{{asset('img/mengetahui.png')}}" alt="Mengetahui">
                        <p id="sign" >{{$assetFiltered->applicant_name}}</p>
                    </div>
                    <div class="approval-item">
                        <h5>Kepala Laboratorium</h5>
                        @if($assetFiltered->is_laboratory_approve != null)
                            <img style="width: 150px;" class="alert alert-success text-black p-1" src="{{asset('img/menyetujui.png')}}" alt="Menyetujui">
                            <div id="sign">
                                <p>{{ $assetFiltered->is_laboratory_approve }}</p></div>
                        @elseif ($assetFiltered->is_reject != null)
                            <img style="width: 150px;" class="alert alert-danger text-white p-1" src="{{asset('img/menolak.png')}}" alt="Menolak">
                        @else 
                            <p class="bg-secondary text-white p-1">Menunggu</p>
                        @endif
                    </div>
                    <div class="approval-item"  id="detskop_div"></div>
                    <div class="approval-item">
                        <h5>Wakil Rektor</h5>
                        @if($assetFiltered->is_wr_approve != null)
                            <img style="width: 150px;" class="alert alert-success text-white p-1" src="{{asset('img/menyetujui.png')}}" alt="Menyetujui">
                            <div id="sign">
                                <p>{{ $assetFiltered->is_wr_approve }}</p></div>
                        @elseif ($assetFiltered->is_reject != null)
                            <img style="width: 150px;" class="alert alert-danger text-white p-1" src="{{asset('img/menolak.png')}}" alt="Menolak">
                        @else 
                            <p class="bg-secondary text-white p-1">Menunggu</p>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-center text-muted">Data peminjaman tidak tersedia.</p>
            @endif
        </main>

        <footer>
        </footer>
    </div>
@endsection
