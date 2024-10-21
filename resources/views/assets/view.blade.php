@extends('layouts.master')

@section('titlePage')
    Info | {{$asset->asset_name}}
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #title {
            display: none;
        }
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 300px;
            height: auto;
            overflow: hidden;
            margin: 0 auto;
        }
        .image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
    </style>
@section('content')
        <h1 class="bg-info p-3 rounded text-left">{!! $qrcode !!}  {{$asset->asset_name}} {{$asset->asset_type}}</h1>
        <div class="row mt-4">
            <!-- Left -->
            <div class="col-lg-4 mb-4">
                <div class="text-left">
                    <h5 class="badge bg-secondary ml-1">
                        @foreach($categories as $category)
                            @if($asset->category_id == $category->category_id)
                                {{ $category->category_name }}
                            @endif
                        @endforeach
                    </h5>
                    <h5 class="badge bg-secondary ml-1">{{ $asset->asset_code }}</h5>
                </div>
                <hr>
                
                <div class="image-container">
                    @if ($asset->asset_image)
                        <img src="{{ asset('/storage/'.$asset->asset_image) }}" alt= {{ $asset->asset_name }}>
                    @else
                        <img src="{{ asset('img/aset.png') }}" alt="Default Image">
                    @endif
                </div>
            </div>

            <!-- Right -->
            <div class="col-lg-8">
                <div>
                    <h5 class="text-primary p-1 rounded">Jumlah</h5>
                    <p class="text-bg-light p-3 rounded">{{$asset->asset_quantity}}</p>

                    <h5 class="text-primary p-1 rounded">Deskripsi</h5>
                    <p class="text-bg-light p-3 rounded">{{$asset->asset_desc}}</p>

                    <h5 class="text-primary p-1 rounded">Lokasi Tersimpan</h5>
                    <p class="text-bg-light p-3 rounded">{{$asset->asset_position}}</p> 
                </div>
            </div>
        </div>
        <div class="col-lg-12 p-2">
            @if (auth()->user()->group_id != 7)
                <h5 class="text-primary p-1 rounded">Jadwal Pemeliharaan</h5>
                <p class="text-bg-light p-3 rounded">{{$asset->maintenance_desc}}</p>
            @endif
            @if (auth()->user()->group_id == 1)
                <h5 class="text-primary p-1 rounded">Harga satuan</h5>
                <p class="text-bg-light p-3 rounded">{{$asset->asset_price}}</p>

                <h5 class="text-primary p-1 rounded">Nomor Resi (BKK)</h5>
                <p class="text-bg-light p-3 rounded">{{$asset->receipt_number}}</p>
            @endif
                <h5 class="text-primary p-1 rounded">Tanggal Masuk</h5>
                <p class="text-bg-light p-3 rounded">{{$asset->asset_date_of_entry}}</p>
            
            @if (auth()->user()->group_id == 1)
                <h5 class="text-primary p-1 rounded">Grafik Peminjaman Terakhir</h5>
                @if($weeklyLoans->isNotEmpty())
                <canvas id="loanChart" width="400" height="200"></canvas>
                @else
                    <p>Belum ada peminjaman terkait aset ini.</p>
                @endif
                <p><b>Kesimpulan: </b>{{ $conclusion ?? 'Data tidak cukup atau belum ada data untuk memberikan kesimpulan.' }}</p>
            @endif
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('asset.index') }}" class="btn btn-md btn-info me-2"><b>Kembali</b></a>
            @if(auth()->user()->group_id == 1)
            <a href="{{ route('asset.label', ['asset_id' => $asset->asset_id]) }}" class="btn btn-secondary btn-md"><i class="fa-solid fa-tag"></i> Buat Label</a>
            @endif
        </div>
        <script>
        // Data dari Controller
        const weeklyLoanLabels = @json($weeklyLoans->keys()); // Minggu (label sumbu X)
        const weeklyLoanData = @json($weeklyLoans->values()); // Jumlah barang yang dipinjam setiap minggu (data sumbu Y)

        const ctx = document.getElementById('loanChart').getContext('2d');

        // Membuat chart SMA
        const loanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: weeklyLoanLabels,
                datasets: [
                    {
                        label: 'Jumlah peminjaman',
                        data: weeklyLoanData,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                    },
                ]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Minggu'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah dipinjam'
                        }
                    }
                }
            }
        });
    </script>
@endsection