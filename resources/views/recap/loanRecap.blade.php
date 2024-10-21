@extends('layouts.master')

@section('titlePage', 'Recap Data Peminjaman')
@section('title', 'Recap Data Peminjaman')
    <style>
        @media print {
            #print-button, #export-xlsx, #export-pdf, h3 {
                display: none;
            }
            h1 {
                page-break-before: always;
            }
            h2 {
                color: black;
            }
        }
        .table-container {
            overflow-x: auto;
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
    <h1 class="text-center my-4">Peminjaman Ruangan</h1>
    <div class="table-container">
        <table id="table-room-assets" class="table table-bordered table-hover mx-auto">
            <thead>
                <tr>
                    <th scope="col" class="bg-info text-center">Nama Ruangan</th>
                    <th scope="col" class="bg-info text-center">Jumlah</th>
                    <th scope="col" class="bg-info text-center">Peminjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Pinjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Dikembalikan</th>
                    <th scope="col" class="bg-info text-center">Deskripsi</th>
                    <th scope="col" class="bg-info text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roomLoan as $room)
                <tr>
                    <td class="text-center">{{ $room->loan_asset_name }}</td>
                    <td class="text-center">{{ $room->loan_asset_quantity }} buah</td>
                    <td class="text-center">{{ $room->applicant_name }}, {{ $room->applicant_position }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($room->loan_date)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($room->loan_length)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $room->loan_desc }}</td>
                    <td class="text-left">{{ $room->loan_note_status }} <hr> <p>{{ $room->using ? $room->using->on_use_desc : 'Belum diambil' }}.</p>  <p> {{ $room->returning && $room->using->on_use_desc == '' ? $room->returning->return_desc : 'Belum Dikembalikan' }}</p></td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-muted" colspan="7">Tidak ada Peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Barang -->
    <h1 class="text-center my-4">Peminjaman Barang</h1>
    <div class="table-container">
        <table id="table-stuff-assets" class="table table-bordered table-hover mx-auto">
            <thead>
                <tr>
                    <th scope="col" class="bg-info text-center">Nama Barang</th>
                    <th scope="col" class="bg-info text-center">Jumlah</th>
                    <th scope="col" class="bg-info text-center">Peminjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Pinjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Dikembalikan</th>
                    <th scope="col" class="bg-info text-center">Deskripsi</th>
                    <th scope="col" class="bg-info text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stuffLoan as $stuff)
                <tr>
                    <td class="text-center">{{ $stuff->loan_asset_name }}</td>
                    <td class="text-center">{{ $stuff->loan_asset_quantity }} buah</td>
                    <td class="text-center">{{ $stuff->applicant_name }}, {{ $stuff->applicant_position }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($stuff->loan_date)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($stuff->loan_length)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $stuff->loan_desc }}</td>
                    <td class="text-left">{{ $stuff->loan_note_status }} <hr> <p>{{ $stuff->using ? $stuff->using->on_use_desc : 'Belum diambil' }}.</p>  <p>{{ $stuff->returning && $stuff->using ? $stuff->returning->return_desc : 'Belum Dikembalikan' }}</p></td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-muted" colspan="7">Tidak ada Peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Peralatan -->
    <h1 class="text-center my-4">Peminjaman Alat</h1>
    <div class="table-container">
        <table id="table-tool-assets" class="table table-bordered table-hover mx-auto">
            <thead>
                <tr>
                    <th scope="col" class="bg-info text-center">Nama Alat</th>
                    <th scope="col" class="bg-info text-center">Jumlah</th>
                    <th scope="col" class="bg-info text-center">Peminjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Pinjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Dikembalikan</th>
                    <th scope="col" class="bg-info text-center">Deskripsi</th>
                    <th scope="col" class="bg-info text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($toolLoan as $tool)
                <tr>
                    <td class="text-center">{{ $tool->loan_asset_name }}</td>
                    <td class="text-center">{{ $tool->loan_asset_quantity }} buah</td>
                    <td class="text-center">{{ $tool->applicant_name }}, {{ $tool->applicant_position }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($tool->loan_date)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($tool->loan_length)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $tool->loan_desc }}</td>
                    <td class="text-left">{{ $tool->loan_note_status }} <hr> <p>{{ $tool->using ? $tool->using->on_use_desc : 'Belum diambil' }}.</p>  <p>{{ $tool->returning && $tool->using ? $tool->returning->return_desc : 'Belum Dikembalikan' }}</p></td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-muted" colspan="7">Tidak ada Peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Kendaraan -->
    <h1 class="text-center my-4">Peminjaman Kendaraan</h1>
    <div class="table-container">
        <table id="table-vehicle-assets" class="table table-bordered table-hover mx-auto">
            <thead>
                <tr>
                    <th scope="col" class="bg-info text-center">Nama Kendaraan</th>
                    <th scope="col" class="bg-info text-center">Jumlah</th>
                    <th scope="col" class="bg-info text-center">Peminjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Pinjam</th>
                    <th scope="col" class="bg-info text-center">Tanggal Dikembalikan</th>
                    <th scope="col" class="bg-info text-center">Deskripsi</th>
                    <th scope="col" class="bg-info text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehicleLoan as $vehicle)
                <tr>
                    <td class="text-center">{{ $vehicle->loan_asset_name }}</td>
                    <td class="text-center">{{ $vehicle->loan_asset_quantity }} buah</td>
                    <td class="text-center">{{ $vehicle->applicant_name }}, {{ $vehicle->applicant_position }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($vehicle->loan_date)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($vehicle->loan_length)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $vehicle->loan_desc }}</td>
                    <td class="text-left">{{ $vehicle->loan_note_status }} <hr> <p>{{ $vehicle->using ? $vehicle->using->on_use_desc : 'Belum diambil' }}.</p>  <p>{{ $vehicle->returning && $vehicle->using ? $vehicle->returning->return_desc : 'Belum Dikembalikan' }}</p></td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-muted" colspan="7">Tidak ada Peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('export-pdf').addEventListener('click', function() {
            window.print();
        });

        document.getElementById('export-xlsx').addEventListener('click', function() {
            let workbook = XLSX.utils.book_new();

            let tables = [
                { id: 'table-room-assets', name: 'Peminjaman Ruangan' },
                { id: 'table-stuff-assets', name: 'Peminjaman Barang' },
                { id: 'table-tool-assets', name: 'Peminjaman Peralatan' },
                { id: 'table-vehicle-assets', name: 'Peminjaman Kendaraan' }
            ];

            tables.forEach(table => {
                let tableElement = document.getElementById(table.id);
                let worksheet = XLSX.utils.table_to_sheet(tableElement);
                XLSX.utils.book_append_sheet(workbook, worksheet, table.name);
            });

            let workbookOut = XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            saveAs(new Blob([s2ab(workbookOut)], { type: "application/octet-stream" }), 'Recap Data_Peminjaman Aset_UTB.xlsx');
        });
    </script>
    <script>
        function changeRangeDate(rangeDate){
            let url = new URL(window.location.href);
            url.searchParams.set('range_date', rangeDate);
            window.location.href = url.toString();
        }
    </script>
@endsection
