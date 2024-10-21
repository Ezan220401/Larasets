@extends('layouts.master')
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <style>
        #sory {
            display: none;
        }
        @media (max-width: 576px) {
            tr th:nth-child(1),
            tr td:nth-child(1),
            tr th:nth-child(5),
            tr td:nth-child(5),
            #forecasting_chart{
                display: none;
            }
            #sory {
                display: block;
            }

            #smaChart {
                width: 100% !important; /* Mengatur lebar canvas 100% dari kontainer */
                height: auto !important; /* Menjaga aspek rasio canvas */
            }
        }

        @media (max-width: 320px) {
            #smaChart {
                width: 100% !important;
                height: auto !important;
            } 
        }

    </style>
@section('titlePage', 'Rekap Data')

@section('title', 'Rekap Data')
@section('content')
    <!-- Recap -->
    <div class="d-flex form-inline justify-content-between">
        <div>
            @if (auth()->user()->group_id == 1)
            <form action="{{ route('asset.recap') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary ml-2">
                <i class="fa-solid fa-print"></i> Rekap Seluruh Data Aset</button>
            </form>
        </div>
         <br>

         <!-- Modal Import Aset dari csv -->
            <div class="d-flex">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#includeAssetModal">
                    <i class="fas fa-plus"></i> Tambah Data Aset dari CSV
                </button>
            </div>
            
            <!-- Modal untuk mengirim CSV -->
            <div class="modal fade" id="includeAssetModal" tabindex="-1" role="dialog" aria-labelledby="includeAssetsLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('data.assetCSV') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="includeAssetsLabel">Konversi dari CSV</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <h5>Kategori Data Aset
                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#selectAssetName" aria-expanded="false" aria-controls="selectAssetName" class="text-primary" style="text-decoration: none;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </h5>
                                <div class="collapse" id="selectAssetName">
                                    <div class="card card-body">
                                        <p>Silahkan pilih kategori untuk seluruh data yang akan diinputkan csv</p>
                                    </div>
                                </div>       
                                <div class="input-group mb-3">
                                    <select name="category_id" class="form-control border border-info" required>
                                        @foreach ($asset_categories as $asset_category)
                                            <option value="{{ $asset_category->category_id }}">
                                                {{ $asset_category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <h5>File CSV
                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#csvAssetInfo" aria-expanded="false" aria-controls="csvAssetInfo" class="text-primary" style="text-decoration: none;">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </h5>
                                <div class="collapse" id="csvAssetInfo">
                                    <div class="card card-body">
                                        <p>Pastikan kolom dipisahkan dengan titik koma ';'. Silahkan pakai ini sebagai acuan pada baris pertama:</p>
                                        <p class="text-warning bg-secondary p-2 border rounded">Nama Aset;Tipe atau Merek;Jumlah Aset;Deskripsi aset;Letak aset disimpan;Nomor resi;Tanggal masuk;Harga satuan</p>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="file" name="csv_file" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Konversi</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        <!-- Filter -->
        <form class="form-inline mt-4" method="get" action="{{ route('data.recap') }}">
            <div class="input-group">
                <!-- Category -->
                <select name="asset_categories" id="assetCategory" class="form-control border border-info">
                    <option value="all">Semua Kategori</option>
                    @foreach ($asset_categories as $asset_category)
                        <option value="{{ $asset_category->category_id }}" {{ request('asset_categories') == $asset_category->category_id ? 'selected' : '' }}>
                            {{ $asset_category->category_name }}
                        </option>
                    @endforeach
                </select>
                <!-- Search -->
                <input type="text" name="search_asset" class="form-control ml-2 border border-info" id="search_asset" placeholder="Masukkan keyword pencarian"
                       value="{{ request('search_asset') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Item Display -->
    <div class="form-inline w-100">
            <label for="assetItemsPerPage">Jumlah data perhalaman</label>
            <select name="assetItemsPerPage" id="assetItemsPerPage" class="form-control border border-secondary ml-2 pr-5" onchange="changeAssetsPerPage(this.value)">
                <option value="5" {{ request()->get('asset_items_per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request()->get('asset_items_per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request()->get('asset_items_per_page') == 15 ? 'selected' : '' }}>15</option>
                <option value="20" {{ request()->get('asset_items_per_page') == 20 ? 'selected' : '' }}>20</option>
            </select>
        </div>   
    <table class="table table-responsive table-bordered table-hover" id="itemsTable">
        <thead class="bg-primary text-white">
            <tr>
                <th class="text-center">Kategori</th>
                <th class="text-center">Nama Aset</th>
                <th class="text-center">Tipe</th>
                <th class="text-center">Deskripsi</th>
                <th class="text-center">Waktu Masuk</th>
                <th class="text-center">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assets as $asset)
                <tr href="{{ route('asset.show', $asset->asset_id) }}">
                    <td class="text-center">
                        @foreach($asset_categories as $asset_category)
                            @if($asset->category_id == $asset_category->category_id)
                                {{ $asset_category->category_name }}
                            @endif
                        @endforeach
                    </td>
                    <td class="text-center">{{ $asset->asset_name }}</td>
                    <td class="text-center">{{ $asset->asset_type }}</td>
                    <td class="text-center">{{ $asset->asset_desc }}. <br><i class="fa-solid fa-map-pin" style="color: #eb0000;"></i> Terletak di <u>{{ $asset->asset_position }}</u></td>
                    <td class="text-center">{{ $asset->asset_date_of_entry }}</td>
                    <td class="text-center">{{ $asset->asset_quantity}}</td>
                </tr>
            @empty
            <tr>
                <td></td>
                <td class="text-center text-muted" style="width: 100%;">Data aset tidak tersedia pada halaman ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $assets->appends(['asset_items_per_page' => request()->get('asset_items_per_page')])->links() }}
    </div>
    <hr>

    <!-- Recap Peminjaman-->
    @if (auth()->user()->group_id == 4)
    <div class="d-flex form-inline justify-content-between">
        <div>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#filterModal">
                <i class="fa-solid fa-print"></i> Rekap Data Peminjaman</button>
            </button>
        </div>
        
    <br>

    <!-- Modal untuk filter -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <form action="{{ route('loan.recap') }}" method="POST" onsubmit="recap(event)">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="radio" name="filter_option" class="m-2" value="date-range" id="date_range" checked>
                            <label for="filterOption" class="p-3">Data Sesuai Rentang Tanggal:</label><br>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Sejak tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">Sampai tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="form-group"> 
                            <input type="radio" name="filter_option" class="m-2" value="all" id="all_data">
                            <label for="filterOption" class="p-3">Tampilkan Semua Data</label><br>
                        </div>
                    </div>
                <button type="submit" class="btn btn-primary m-2">Rekap</button>
                <a href="{{ route('data.recap') }}" class="btn btn-md btn-danger me-2"><b>Batal</b></a>
            </form>

            </div>
        </div>
    </div>
    
   <!-- Modal Import Peminjaman dari CSV -->
    <div class="d-flex">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#includeLoanModal">
            <i class="fas fa-plus"></i> Tambah Data Peminjaman dari CSV
        </button>
    </div>
    
    <!-- Modal untuk mengirim CSV -->
    <div class="modal fade" id="includeLoanModal" tabindex="-1" aria-labelledby="includeLoansLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('data.loanCSV') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="includeLoansLabel">Konversi dari CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Warning Text -->
                        <p class="text-warning bg-secondary p-2 border rounded text-center">
                            Import ini untuk menginput data peminjaman yang sudah selesai
                        </p>
    
                        <!-- Kategori Peminjaman -->
                        <div class="mb-3">
                            <h5>Kategori Peminjaman
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#selectLoanName" aria-expanded="false" aria-controls="selectLoanName" class="text-primary" style="text-decoration: none;">
                                    <i class="fa-solid fa-circle-info"></i>
                                </a>
                            </h5>
                            <div class="collapse" id="selectLoanName">
                                <div class="card card-body">
                                    <p>Silahkan pilih kategori untuk seluruh data yang akan diinputkan CSV.</p>
                                </div>
                            </div>
                            <select name="loan_name" class="form-control border border-info" required>
                                @foreach ($loan_categories as $loan_category)
                                    <option value="{{ $loan_category->category_name }}">
                                        {{ $loan_category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- File CSV -->
                        <div class="mb-3">
                            <h5>File CSV
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#csvLoanInfo" aria-expanded="false" aria-controls="csvLoanInfo" class="text-primary" style="text-decoration: none;">
                                    <i class="fa-solid fa-circle-info"></i>
                                </a>
                            </h5>
                            <div class="collapse" id="csvLoanInfo">
                                <div class="card card-body">
                                    <p>Pastikan kolom dipisahkan dengan titik koma ';'. Silahkan pakai ini sebagai acuan pada baris pertama:</p>
                                    <p class="text-warning bg-secondary p-2 border rounded">Nama pengaju; Jabatan pengaju; Nomor telepon pengaju; ID pengaju; Aset yang dipinjam; Jumlah yang dipinjam; Deskripsi peminjaman; Dipinjam/dipakai di mana; Kesepakatan waktu pakai (Tanggal/Bulan/Tahun Jam:Menit); Kesepakatan waktu selesai (Tanggal/Bulan/Tahun Jam:Menit)</p>
                                </div>
                            </div>
                            <input type="file" name="csv_file" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Konversi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif


        <!-- Filter -->
        <form class="form-inline mt-4" method="get" action="{{ route('data.recap') }}">
            <div class="input-group">
                <!-- Category -->
                <select name="loan_categories" id="loanCategory" class="form-control border border-info">
                    <option value="all">Semua Kategori</option>
                    @foreach ($loan_categories as $loan_category)
                        <option value="{{ $loan_category->category_name }}" {{ request('loan_categories') == $loan_category->category_id ? 'selected' : '' }}>
                            {{ $loan_category->category_name }}
                        </option>
                    @endforeach
                </select>
                <!-- Search -->
                <input type="text" name="search_loan" class="form-control ml-2 border border-info" id="search_loan" placeholder="Masukkan keyword pencarian"
                       value="{{ request('search_loan') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                </div>
            </div>
        </form>
        <!-- Item Display -->
    <div class="form-inline w-100">
            <label for="loanItemsPerPage">Jumlah data perhalaman</label>
            <select name="loanItemsPerPage" id="loanItemsPerPage" class="form-control border border-secondary ml-2 pr-5" onchange="changeLoansPerPage(this.value)">
                <option value="5" {{ request()->get('loan_items_per_page') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request()->get('loan_items_per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request()->get('loan_items_per_page') == 15 ? 'selected' : '' }}>15</option>
                <option value="20" {{ request()->get('loan_items_per_page') == 20 ? 'selected' : '' }}>20</option>
            </select>
        </div>   
    <table class="table table-responsive table-bordered table-hover" id="itemsTable">
        <thead class="bg-primary text-white">
            <tr>
                <th class="text-center">Kategori</th>
                <th class="text-center">Aset yang dipinjam</th>
                <th class="text-center">Nama peminjam</th>
                <th class="text-center">Keperluan</th>
                <th class="text-center">Waktu peminjaman</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $loan)
                <tr href="{{ route('loans.show', $loan->loan_id) }}">
                    <td class="text-center">{{ $loan->loan_name }}</td>
                    <td class="text-center">{{ $loan->loan_asset_quantity }} {{ $loan->loan_asset_name }}</td>
                    <td class="text-center">{{ $loan->applicant_name }} ({{ $loan->applicant_position }})</u></td>
                    <td class="text-center">{{ $loan->loan_desc }}. Dipakai di {{ $loan->loan_position }}</td>
                    <td class="text-center">{{ $loan->loan_date }} sampai {{ $loan->loan_length }}</td>
                    <td class="text-center">{{ $loan->loan_note_status }}</td>
                </tr>
            @empty
            <tr>
                <td></td>
                <td class="text-center text-muted" style="width: 100%;">Data peminjaman tidak tersedia pada halaman ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $loans->appends(['loan_items_per_page' => request()->get('loan_items_per_page')])->links() }}
    </div>
    <hr>

    <!-- Evaluasi Data -->
    <table class="table table-bordered table-hover mt-3">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Minggu</th>
                <th scope="col">Jumlah Pinjaman</th>
                <th scope="col">SMA (3 periode)</th>
                <th scope="col">SMA (5 periode)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actualData as $index => $actual)
                <tr>
                    <td>{{ $weekLabels[$index] ?? '-' }}</td>
                    <td>{{ $actual }}</td>
                    <td>{{ isset($shiftedSma3[$index]) ? number_format($shiftedSma3[$index], 2) : 'Data belum cukup' }}</td>
                    <td>{{ isset($shiftedSma5[$index]) ? number_format($shiftedSma5[$index], 2) : 'Data belum cukup' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Tidak ada data untuk ditampilkan</td>
                </tr>
            @endforelse
            <tr class="bg-secondary">
            <td><b>Perkiraan sistem</b></td>
                @if($sma3forcecast < $sma5forcecast)
                    <td>{{ $sma3forcecast }} hingga {{ $sma5forcecast }} pengajuan</td>
                @elseif($sma3forcecast > $sma5forcecast)
                    <td>{{ $sma5forcecast }} hingga {{ $sma3forcecast }} pengajuan</td>
                @else
                    <td>{{ $sma5forcecast }} pengajuan</td>
                @endif
                <td class="bg-warning">MPE rata-rata {{$mpeSma3}} %</td>
                <td class="bg-warning">MPE rata-rata {{$mpeSma5}} %</td>
            </tr>
        </tbody>
    </table>
    </div>
    
    

<!--Evaluasi Data-->
    <div class="container">
        <a href="#evaluation" class="btn btn-primary" data-toggle="collapse">Tampilkan Evaluasi Data</a>
        <div id="evaluation" class="collapse">
    
        <div class="mt-4 card p-2 bg-light" id="forecasting_chart">
            <h2 class="text-center text-black p-2 bg-info">Pengajuan 6 Bulan terakhir</h2>
            <div class="chart-container" style="position: relative;  height:auto;  overflow-x: auto;">
                <canvas id="smaChart" width="auto" height="100px">></canvas>
            </div>
        </div>
        <h6 id="sory" class="text-center p-3 m-1 bg-warning rounded">Maaf, saat ini Grafik tidak dapat ditampilkan pada layar mobile</h6>


            <!-- Kesimpulan dari Grafik -->
            <div class="mt-4 card p-2 bg-light">
                <h2 class="text-center text-black p-2 bg-info">Kesimpulan dari Grafik</h2>
                <p>Tren: {{ $trend == 'increasing' ? 'Meningkat' : 'Menurun' }}</p>
                <p>Puncak pengajuan tertinggi terjadi pada:</p>
                <ul>
                    @foreach ($peaks as $peakIndex)
                        <li>                            
                            <b>
                                Pada {{ $peekOnDate[$peakIndex] }} terdapat pengajuan
                            </b>
                            <ul>
                                @if(isset($weeklyAssets[$peekOnDate[$peakIndex]]))
                                    @foreach ($weeklyAssets[$peekOnDate[$peakIndex]] as $assetName => $count)
                                        <li>{{ $assetName }} dipakai {{ $count }} kali</li>
                                    @endforeach
                                @else
                                    <li>Tidak ada data pengajuan</li>
                                @endif
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4 card p-2 bg-light">
                <h2 class="text-center text-black p-2 bg-info">Pengajuan paling sering dalam beberapa bulan terakhir</h2>
                <ul>
                    @foreach ($mostFrequentLoans as $loanName => $count)
                        <li>{{ $loanName }} - {{ $count }} kali</li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4 card p-2 bg-light">
                    <h2 class="text-center text-black p-2 bg-info">Saran dari Laraset</h2>
                    <ul>
                    @php
                        $counter = 0;
                    @endphp

                    @foreach ($asset_data as $asset)
                        @php
                            $assetLoanCount = $mostFrequentLoans[$asset->asset_name] ?? 0;
                            $assetAge = Carbon\Carbon::parse($asset->asset_date_of_entry)->diffInYears(now());
                        @endphp

                        <!-- saran untuk penambahan jumlah aset  -->
                        @if ($assetLoanCount >= $asset->asset_quantity)
                            <li class="bg-warning p3">Lebih baik menambah jumlah {{ $asset->asset_name }} tipe {{ $asset->asset_type }} karena sering dipakai</li>
                        @endif

                        <!-- saran untuk pembaruan atau penggantian aset yang berumur lebih dari 10 tahun  -->
                        @if ($assetAge > 10)
                            <li>Lebih baik memperbarui atau mengganti {{ $asset->asset_name }} {{ $asset->asset_type }} karena cukup lawas</li>
                        @endif

                        @if ($assetAge < 10 && $assetLoanCount <= $asset->asset_quantity)
                            @if ($counter == 0)
                            @php
                                $counter++;
                            @endphp
                                @else
                            @break
                                @endif
                        @endif

                    @endforeach
                    </ul>
                </div>    
            </div>
        </div>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script>
    function changeAssetsPerPage(assetItemsPerPage){
        var url = new URL(window.location.href);
        url.searchParams.set('asset_items_per_page', assetItemsPerPage);
        window.location.href = url.toString();
    }
</script>
<script>
    function changeLoansPerPage(loanItemsPerPage){
        var url = new URL(window.location.href);
        url.searchParams.set('loan_items_per_page', loanItemsPerPage);
        window.location.href = url.toString();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('smaChart').getContext('2d');
            const smaChart = new Chart(ctx, {
                type: 'line',
                data: {
                labels: @json($weeks),
                datasets: [{
                    label: 'Jumlah pengajuan',
                    data: @json($actualData),
                    borderColor: 'rgba(0, 0, 132, 1)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Forecasting 3 periode',
                    data: @json($shiftedSma3),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Forecasting 5 periode',
                    data: @json($shiftedSma5),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false
                }
            ]
            },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Peminjaman'
                            }
                        },
                        x: {
                            title: {
                                display:true,
                                text:  'Tanggal Awal Minggu'
                            }
                        }
                    }   
                }
            });
        });
</script>
<script>
function recap(event) {
        event.preventDefault();

        const radioDateRange = document.getElementById('date_range').checked;
        const startDateInput = document.getElementById('start_date').value;
        const endDateInput = document.getElementById('end_date').value;

        if (radioDateRange) {
            if (!startDateInput || !endDateInput) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silahkan lengkapi tanggal',
                });
                return false;
            } else {
                event.target.submit();
            }
        } else {
            event.target.submit();
        }
    }
</script>
    
@endsection