@extends('layouts.master')

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/moment-timezone@0.5.34/builds/moment-timezone-with-data.min.js'></script>

<style>
    #sory {
        display: none;
    }
    @media (max-width: 576px) {
        #span {
            display: block;
        }

        tr th:nth-child(1),
        tr td:nth-child(1) {
            display: none;
        }
        #calendar-collapsed{
            display:none;
        }
        #calendar-container {
            font-size: 14px; 
            padding: 10px;
            overflow-x: auto;
            white-space: nowrap;
            width: 100%;
            display: none;
        }
    
        #calendar-body {
            min-width: 500px; 
        }
    
        .card-link {
            font-size: 16px;
        }
        
        #sory {
            display: block;
        }
    }

</style>

@section('titlePage', 'Dashboard')

@section('title', 'Dashboard')

@section('content')
        <h5>Selamat Datang <b>{{ Auth::user()->user_name }}</b>, anda Login sebagai <b>{{ $group_name }}</b>.</h5>
        <hr style="border: 1.5px solid blue;">
        <div class="input-group mb-3">
            <form class="form-inline mt-4" method="get" action="{{ route('home.index') }}">
                <div class="input-group">
                    <select name="categories" id="categoryFilter" class="form-control mr-2 border border-info">
                        <option value="all">Semua Kategori</option>
                        <option value="Peminjaman Alat" {{ request('categories') == 'Peminjaman Alat' ? 'selected' : '' }}>Peminjaman Alat</option>
                        <option value="Peminjaman Barang" {{ request('categories') == 'Peminjaman Barang' ? 'selected' : '' }}>Peminjaman Barang</option>
                        <option value="Peminjaman Ruangan" {{ request('categories') == 'Peminjaman Ruangan' ? 'selected' : '' }}>Peminjaman Ruangan</option>
                        <option value="Peminjaman Laboratorium" {{ request('categories') == 'Peminjaman Laboratorium' ? 'selected' : '' }}>Peminjaman Laboratorium</option>
                        <option value="Peminjaman Kendaraan" {{ request('categories') == 'Peminjaman Kendaraan' ? 'selected' : '' }}>Peminjaman Kendaraan</option>
                    </select>
                    <input type="text" name="search" class="form-control ml-2 border border-info" id="search" placeholder="Masukkan keyword" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                    </div>
                </div>
            </form>
        </div>

        @if(in_array(auth()->user()->group_id, [1, 2, 3, 4, 8, 9, 10, 11, ]))
        <hr>
            <div>
                <form action="{{ route('data.recap') }}" method="GET" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary ml-2">
                    <i class="fa-solid fa-print"></i> Rekap Data Aset dan Peminjaman</button>
                </form>
            </div>
        <hr>
        @endif

        <!-- Peminjaman Pribadi -->
            <h3 class="text-center">Pengajuan Peminjaman Pribadi</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-info text-white text-center">
                        <th>Peminjaman</th>
                        <th>Pengaju</th>
                        <th>Aset yang diajukan</th>
                        <th>Deskripsi</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                    <div>
                        <a href="{{ route('loans.create') }}" class="btn btn-md btn-success mb-3 float-right">
                            <i class="fas fa-plus"></i> Pengajuan Baru
                        </a>
                    </div>
                    <tbody>
                    @forelse ($loan_user as $loan)
                    <tr>
                        <td class="text-center">{{ $loan->loan_name }}</td>
                        <td class="text-center">{{ $loan->applicant_name }}, {{ $loan->applicant_position }}</td>
                        <td class="text-center">{{ $loan->loan_asset_quantity }} {{ $loan->loan_asset_name }}</td>
                        <td class="text-center">{{ \Illuminate\Support\Str::limit($loan->loan_desc, 70) }}</td>
                        <td class="text-center">{{ $loan->translated_date }}</td>
                        <td class="text-center">{{ $loan->translated_length }}</td>
                        <td class="text-center">{{ $loan->loan_note_status == 'Kadaluarsa' ? 'Peminjaman sudah selesai' : $loan->loan_note_status }}</td>
                        <td class="text-center">
                            <form onsubmit = "return cancelAlert(event, '{{ $loan->loan_name }}', '{{ $loan->loan_asset_name }}');" action="{{ route('loans.destroy', $loan->loan_id) }}" method="POST">
                                <a href="{{ route('loans.show', $loan->loan_id) }}" class="btn btn-info btn-md mb-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($loan->loan_note_status == '|Menunggu Persetujuan| ')
                                <a href="{{ route('loans.edit', $loan->loan_id) }}" class="btn btn-warning btn-md mb-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if ($loan->is_full_approve == true && $loan->is_returned == false && $loan->is_using == false)
                                <a href="{{ route('using.form', $loan->loan_id) }}" class="btn btn-secondary btn-md mb-1 btn-icon">
                                    <i class="fas fa-hand-paper"></i>
                                </a>
                                @endif
                                
                                @if ($loan->is_using == true && $loan->is_returned == false)
                                <a href="{{ route('returning.form', $loan->loan_id) }}" class="btn btn-secondary btn-md mb-1 btn-icon">
                                    <i class="fas fa-reply"></i>
                                </a>
                                @endif

                                @csrf
                                @method('DELETE')
                                @if ($loan->loan_note_status != 'Kadaluarsa' && $loan->is_using == false)
                                <button type="submit" class="btn btn-danger btn-md mb-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                                
                            </form>
                        </td> 
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted" colspan="8">Tidak ada peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $loan_user->links() }}
        </div>

<!-- Bagian tambahan untuk Admin -->
        @if(in_array(auth()->user()->group_id, [1, 2, 3, 4, 8, 9, 10, 11, ]))

        <hr style="border: 1.5px solid blue;">

        <!-- Peminjaman hari ini -->
        <div class="p-2">
            <h3>Jadwal Peminjaman Hari Ini</h3>
            @if ($loans_today->isNotEmpty())
                @foreach ($loans_today as $loan_today)
                    @if ($loan_today->is_full_approve)
                        @if (!$loan_today->is_using && !$loan_today->is_returned)
                            <p>{{ $loan_today->loan_asset_quantity }} {{ $loan_today->loan_asset_name }} seharusnya akan diambil oleh {{ $loan_today->applicant_name }} ({{ $loan_today->applicant_position }})<a href="{{ route('loans.show', $loan_today->loan_id) }}" style="text-decoration:none" class="badge badge-info">Lihat</a></p>
                        @elseif ($loan_today->is_using && !$loan_today->is_returned)
                            <p>{{ $loan_today->loan_asset_quantity }} {{ $loan_today->loan_asset_name }} sedang dipakai {{ $loan_today->applicant_name }} ({{ $loan_today->applicant_position }})<a href="{{ route('loans.show', $loan_today->loan_id) }}" style="text-decoration:none" class="badge badge-info">Lihat</a></p>
                        @elseif ($loan_today->is_using && $loan_today->is_returned)
                            <p>{{ $loan_today->loan_asset_quantity }} {{ $loan_today->loan_asset_name }} seharusnya sudah dikembalikan {{ $loan_today->applicant_name }} ({{ $loan_today->applicant_position }})<a href="{{ route('loans.show', $loan_today->loan_id) }}" style="text-decoration:none" class="badge badge-info">Lihat</a></p>
                        @endif
                    @endif
                @endforeach
            @else
                <p><i>Tidak ada jadwal peminjaman hari ini</i></p>
            @endif
        </div>

        <!-- Kalender peminjaman -->
        @if(in_array(auth()->user()->group_id, [1, 2, 3, 4, 8, 9, 10, 11]))
        <div class="pl-2">
            <h6 id="sory" class="text-center p-3 m-1 bg-warning rounded">Maaf, saat ini kalender tidak dapat ditampilkan pada layar mobile</h6>
            <div class="card" id="calendar-collapsed">
                <a href="#calendar" class="collapsed card-link" data-toggle="collapse">Tampilkan Kalender Acara</a>
            </div>
            <div id="calendar" class="collapse" aria-labelledby="Kalender Acara" data-parent="#calendar-collapsed">
                <div class="bg-light p-3 card-body">
                    <div id="calendar-container" class="table-responsive">
                        <div id="calendar-body"></div>
                    </div>
                </div>
            </div>
        </div>
        <hr style="border: 1.5px solid blue;">
        @endif

<!-- Pengajuan dari pihak pekerja kampus -->
        <h3 class="text-center p-3 m-1 bg-primary rounded">Pengajuan dari Pihak Kampus</h3>
        <div class="table-responsive bg-light border p-2">
            <div class="form-inline w-100">
                <label for="itemsPerPage">Jumlah data perhalaman</label>
                <select name="itemsPerPage" id="itemsPerPage" class="form-control border border-secondary ml-2 pr-5" onchange="changeItemsPerPage(this.value)">
                    <option value="10" {{ request()->get('items_per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request()->get('items_per_page') == 15 ? 'selected' : '' }}>15</option>
                    <option value="20" {{ request()->get('items_per_page') == 20 ? 'selected' : '' }}>20</option>
                </select>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-info text-white text-center">
                        <th>Peminjaman</th>
                        <th>Pengaju</th>
                        <th>Hendak Meminjam</th>
                        <th>Deskripsi</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campus_loan_status as $campus)
                    <tr>
                    <td class="text-center">{{ $campus->loan_name }}</td>
                        <td class="text-center">{{ $campus->applicant_name }}, {{ $campus->applicant_position }}</td>
                        <td class="text-center">{{ $campus->loan_asset_quantity }} {{ $campus->loan_asset_name }}</td>
                        <td class="text-center">{{ \Illuminate\Support\Str::limit($campus->loan_desc, 70) }}</td>
                        <td class="text-center">{{ $campus->translated_date }}</td>
                        <td class="text-center">{{ $campus->translated_length }}</td>
                        <td class="text-center">{{ $campus->loan_note_status == 'Kadaluarsa' ? 'Peminjaman sudah selesai' : $campus->loan_note_status }}</td>
                        <td class="text-center">
                            <a href="{{ route('loans.show', $campus->loan_id) }}" class="btn btn-info btn-md">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted" colspan="5">Tidak ada pengajuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $campus_loan_status->appends(['items_per_page' => request()->get('items_per_page')])->links() }}
        </div>

        <hr style="border: 1.5px solid blue;">

<!-- Pengajuan dari pihak Mahasiswa -->
        <h3 class="text-center p-3 m-1 bg-primary rounded">Pengajuan dari Pihak Mahasiswa</h3>
        <div class="table-responsive bg-light border p-2">
            <div class="form-inline w-100">
                <label for="itemsPerPage">Jumlah data perhalaman</label>
                <select name="itemsPerPage" id="itemsPerPage" class="form-control border border-secondary ml-2 pr-5" onchange="changeItemsPerPage(this.value)">
                    <option value="10" {{ request()->get('items_per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request()->get('items_per_page') == 15 ? 'selected' : '' }}>15</option>
                    <option value="20" {{ request()->get('items_per_page') == 20 ? 'selected' : '' }}>20</option>
                </select>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-info text-white text-center">
                        <th>Peminjaman</th>
                        <th>Pengaju</th>
                        <th>Hendak Meminjam</th>
                        <th>Deskripsi</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student_loan_status as $student)
                    <tr>
                        <td class="text-center">{{ $student->loan_name }}</td>
                        <td class="text-center">{{ $student->applicant_name }}, {{ $student->applicant_position }}</td>
                        <td class="text-center">{{ $student->loan_asset_quantity }} {{ $student->loan_asset_name }}</td>
                        <td class="text-center">{{ \Illuminate\Support\Str::limit($student->loan_desc, 70) }}</td>
                        <td class="text-center">{{ $student->translated_date }}</td>
                        <td class="text-center">{{ $student->translated_length }}</td>
                        <td class="text-center">{{ $student->loan_note_status == 'Kadaluarsa' ? 'Peminjaman sudah selesai' : $student->loan_note_status }}</td>
                        <td class="text-center">
                            <a href="{{ route('loans.show', $student->loan_id) }}" class="btn btn-info btn-md">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted" colspan="5">Tidak ada pengajuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $student_loan_status->appends(['items_per_page' => request()->get('items_per_page')])->links() }}
        </div>

        <hr style="border: 1.5px solid blue;">
        @endif

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let categoryFilter = document.getElementById('categoryFilter');
        categoryFilter.addEventListener('change', function() {
            let selectedCategory = categoryFilter.value;
            filterByCategory(selectedCategory);
        });

        function filterByCategory(category) {
            let rows = document.querySelectorAll('.loan-row');
            rows.forEach(function(row) {
                let categoryCell = row.dataset.category.toLowerCase();
                if (category === 'all' || category === categoryCell) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function(){
        $('#calendar').on('shown.bs.collapse', function(){
            let calendarEl = document.getElementById('calendar-body');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'Asia/Jakarta',
                initialView: 'dayGridMonth',
                events: '/api/events',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventClick: function(info) {
                    window.location.href = '/loan/letter/' + info.event.id;
                }
            });

            calendar.render();
        });
    });
</script>
<script>
    function changeItemsPerPage(itemsPerPage){
        let url = new URL(window.location.href);
        url.searchParams.set('items_per_page', itemsPerPage);
        window.location.href = url.toString();
    }
</script>
<script>
    function cancelAlert(event, loanName, loanAssetName){
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: 'Apakah anda yakin ingin membatalkan ' + loanName + ' ' + loanAssetName + '?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Tidak Jadi!'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Berhasil!','Data berhasil dihapus.',
                    'success').then(() => {
                        form.submit();
                });
            }
        });
    }
</script>
