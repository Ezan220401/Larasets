@extends('layouts.master')

@section('titlePage', 'Recap Data Aset')
@section('title', 'Recap Data Aset')
    <style>
        @media print{
            #print-button, #export-xlsx, #export-pdf, h3, hr{
                display: none;
            }
            h1 {
                page-break-before: always;
            }
            h2 {
                color: black;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
<div id="#print-button" class="container mt-3">
    <h3 class="mt-3">Download</h3>
    <button id="export-xlsx" class="btn btn-success">Excel</button>
    <button id="export-pdf" class="btn btn-danger">PDF</button>
</div>

<!-- Cover -->
<div class="card col-md-11 mx-auto border-secondary p-2 mt-3">
    <h2 style="background-color: blue; color: white;">Daftar Aset Universitas Teknologi Bandung</h2>
    <p>Diperbarui tanggal {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
</div>

    <!-- Ruangan -->
    <div class="bg-light">
        <h1 class="text-center my-4">Daftar Ruangan yang dimiliki UTB</h1>
        <table id="table-room-assets" class="table table-bordered table-hover col-sm-11 mx-auto">
            <thead>
                <tr>
                    <th scope="col" class="bg-info text-center">Nama Ruangan</th>
                    <th scope="col" class="bg-info text-center">Jumlah</th>
                    <th scope="col" class="bg-info text-center">Harga Aset</th>
                    <th scope="col" class="bg-info text-center">Deskripsi</th>
                    <th scope="col" class="bg-info text-center">Waktu Masuk</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roomAsset as $room)
                <tr>
                    <td class="text-center">Ruangan {{ $room->asset_name }} (tipe: {{ $room->asset_type }})</td>
                    <td class="text-center">{{ $room->asset_quantity }} unit</td>
                    <td class="text-center">{{ number_format($room->asset_price) }} IDR</td>
                    <td class="text-center">Memiliki {{ $room->asset_desc }} dan terletak di {{ $room->asset_position }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($room->asset_date_of_entry)->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-muted" colspan="5">Data asset tidak tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <hr>

    <!-- Barang -->
    <div class="bg-light">
        <h1 class="text-center my-4">Daftar Barang yang dimiliki UTB</h1>
        <table id="table-stuff-assets" class="table table-bordered table-hover col-sm-11 mx-auto">
        <thead>
            <tr>
                <th scope="col" class="bg-info text-center">Nama Barang</th>
                <th scope="col" class="bg-info text-center">Jumlah</th>
                <th scope="col" class="bg-info text-center">Harga Tiap Satuan</th>
                <th scope="col" class="bg-info text-center">Deskripsi</th>
                <th scope="col" class="bg-info text-center">Waktu masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stuffAsset as $stuff)
            <tr>
                <td class="text-center">{{ $stuff->asset_name}} {{ $stuff->asset_type}}</td>
                <td class="text-center">{{ $stuff->asset_quantity}} buah</td>
                <td class="text-center">{{ $stuff->asset_price}} IDR</td>
                <td class="text-center">{{ $stuff->asset_desc}} yang disimpan di {{ $stuff->asset_position}}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($stuff->asset_date_of_entry)->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td class="text-center text-mute" colspan="4">Data asset tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    <hr>

    <!-- Peralatan -->
    <div class="bg-light">
        <h1 class="text-center my-4">Daftar Peralatan yang dimiliki UTB</h1>
        <table id="table-tool-assets" class="table table-bordered table-hover col-sm-11 mx-auto">
        <thead>
            <tr>
                <th scope="col" class="bg-info text-center">Nama Alat</th>
                <th scope="col" class="bg-info text-center">Jumlah</th>
                <th scope="col" class="bg-info text-center">Harga Tiap Satuan</th>
                <th scope="col" class="bg-info text-center">Deskripsi</th>
                <th scope="col" class="bg-info text-center">Waktu masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($toolAsset as $tool)
            <tr>
                <td class="text-center">{{ $tool->asset_name}} {{ $tool->asset_type}}</td>
                <td class="text-center">{{ $tool->asset_quantity }} buah</td>
                <td class="text-center">{{ $tool->asset_price}} IDR</td>
                <td class="text-center">{{ $tool->asset_desc}} dan terletak di {{ $tool->asset_position}}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($tool->asset_date_of_entry)->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td class="text-center text-mute" colspan="4">Data asset tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    <hr>
    
    <!-- Kendaraan -->
    <div class="bg-light">
        <h1 class="text-center my-4">Daftar Kendaraan yang dimiliki UTB</h1>
        <table id="table-vehicle-assets" class="table table-bordered table-hover col-sm-11 mx-auto">
        <thead>
            <tr>
                <th scope="col" class="bg-info text-center">Nama Kendaraan</th>
                <th scope="col" class="bg-info text-center">Jumlah</th>
                <th scope="col-xs-2" class="bg-info text-center">Harga Satuan</th>
                <th scope="col" class="bg-info text-center">Deskripsi</th>
                <th scope="col" class="bg-info text-center">Waktu masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vehicleAsset as $vehicle)
            <tr>
                <td class="text-center">Kendaraan {{ $vehicle->asset_name}} {{ $vehicle->asset_type}}</td>
                <td class="text-center">{{ $vehicle->asset_quantity}} unit</td>
                <td class="text-center">{{ $vehicle->asset_price}} IDR</td>
                <td class="text-center">{{ $vehicle->asset_desc}} dan terletak di {{ $vehicle->asset_position}}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($vehicle->asset_date_ofentry)->format('d-m-y') }}</td>
            </tr>
            @empty
            <tr>
                <td class="text-center text-mute" colspan="4">Data asset tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <!-- FileSaver.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
    <!-- SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        document.getElementById('export-pdf').addEventListener('click', function() {
            window.print();
        });
    </script>
    <script>
        document.getElementById('export-xlsx').addEventListener('click', function() {
            let make_document = XLSX.utils.book_new();

            let table1 = document.getElementById('table-room-assets');
            let sheet1 = XLSX.utils.table_to_sheet(table1);
            XLSX.utils.book_append_sheet(make_document, sheet1, 'Ruangan');

            let table2 = document.getElementById('table-stuff-assets');
            let sheet2 = XLSX.utils.table_to_sheet(table2);
            XLSX.utils.book_append_sheet(make_document, sheet2, 'Barang');

            let table3 = document.getElementById('table-tool-assets');
            let sheet3 = XLSX.utils.table_to_sheet(table3);
            XLSX.utils.book_append_sheet(make_document, sheet3, 'Peralatan');

            let table4 = document.getElementById('table-vehicle-assets');
            let sheet4 = XLSX.utils.table_to_sheet(table4);
            XLSX.utils.book_append_sheet(make_document, sheet4, 'Kendaraan');

            let make_document_out = XLSX.write(make_document, {bookType:'xlsx', type:'binary'});
            
            function s2ab(s) {
                let buffer = new ArrayBuffer(s.length);
                let view = new Uint8Array(buffer);
                for (let i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buffer;
            }
            
            saveAs(new Blob([s2ab(make_document_out)],{type:"application/octet-stream"}), 'daftar_asset_UTB.xlsx');
        });

       
    </script>
@endsection