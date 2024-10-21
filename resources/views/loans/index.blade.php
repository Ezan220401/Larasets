@extends('layouts.master')

@section('titlePage', 'Riwayat Peminjaman')

@section('title', 'Riwayat Peminjaman')

@section('content')

<!-- Tambah pengajuan -->
<div class="pr-5">
    <a href="{{ route('loans.create') }}" class="btn btn-md btn-success mb-3 float-right">
        <i class="fas fa-plus"></i> Pengajuan Baru
    </a>
</div>

<!-- Pencarian -->
<form class="form-inline float-right col-md-4 mb-3" method="get" action="/loans">
    <div class="input-group">
        <input type="text" name="search" class="form-control border border-info" id="search" placeholder="Kata yang dicari"
            value="{{ request('search') }}">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        </div>
    </div>
</form>   

<!-- Tabel pengajuan -->
<div class="table-responsive">
    <div class="form-inline w-100">
        <label for="itemsPerPage">Jumlah data yang ditampilkan</label>
        <select name="itemsPerPage" id="itemsPerPage"  class="form-control border border-secondary ml-2 pr-5" onchange="changeItemsPerPage(this.value)">
            <option value="5" {{ request()->get('items_per_page') == 5 ? 'selected' : ''}}>5</option>
            <option value="10" {{ request()->get('items_per_page') == 10 ? 'selected' : ''}}>10</option>
            <option value="15" {{ request()->get('items_per_page') == 15 ? 'selected' : ''}}>15</option>
            <option value="20" {{ request()->get('items_per_page') == 20 ? 'selected' : ''}}>20</option>
        </select>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col" class="bg-info text-center">Peminjaman</th>
                <th scope="col" class="bg-info text-center">Pengaju</th>
                <th scope="col" class="bg-info text-center">Nama Aset</th>
                <th scope="col" class="bg-info text-center">Jumlah</th>
                <th scope="col" class="bg-info text-center">Deskripsi</th>
                <th scope="col" class="bg-info text-center">Tgl Pinjam</th>
                <th scope="col" class="bg-info text-center">Tgl Kembali</th>
                <th scope="col" class="bg-info text-center">Status</th>
                <th scope="col" class="bg-info text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $loan)
            <tr>
                <td class="text-center">{{ $loan->loan_name}}</td>
                <td class="text-center">
                    {{ $loan->applicant_name }} seorang {{ $loan->applicant_position }}
                </td>
                <td class="text-center">{{ $loan->loan_asset_name }}</td>
                <td class="text-center">{{ $loan->loan_asset_quantity }}</td>
                <td class="text-center">{{ $loan->loan_desc }}</td>
                <td class="text-center">{{ $loan->translated_date }}</td>
                <td class="text-center">{{ $loan->translated_length }}</td>
                <td class="text-center">{{ $loan->loan_note_status == 'Kadaluarsa' ? 'Peminjaman sudah selesai' : $loan->loan_note_status }}</td>
                <td class="text-center col-md-5" style="width: 30px">
                    <form onsubmit = "return cancelAlert(event, '{{ $loan->translated_date }}', '{{ $loan->loan_asset_name }}');" action="{{ route('loans.destroy', $loan->loan_id) }}" method="POST">
                        <a href="{{ route('loans.show', $loan->loan_id) }}" style="width: 130px" class="btn btn-info btn-md mb-1">
                            <i class="fas fa-eye"></i> Lihat
                        </a>

                        <!-- Ketentuan pembaruan -->
                        @if ($loan->loan_note_status == '|Menunggu Persetujuan| ')
                        <a href="{{ route('loans.edit', $loan->loan_id) }}" style="width: 130px" class="btn btn-warning btn-md mb-1">
                            <i class="fas fa-edit"></i> Perbarui
                        </a>
                        @endif

                        <!-- ketentuan pengambilan -->
                        @if ($loan->is_full_approve == true && $loan->is_returned == false && $loan->is_using == false)
                        <a href="{{ route('using.form', $loan->loan_id) }}" style="width: 130px" class="btn btn-secondary btn-md mb-1 btn-icon">
                            <i class="fas fa-hand-paper"></i> Mengambil
                        </a>
                        @endif
                        
                        <!-- ketentuan pengembalian -->
                        @if ($loan->is_using == true && $loan->is_returned == false)
                        <a href="{{ route('returning.form', $loan->loan_id) }}" style="width: 130px" class="btn btn-secondary btn-md mb-1 btn-icon">
                            <i class="fas fa-reply"></i> Kembalikan
                        </a>
                        @endif

                        @csrf
                        @method('DELETE')
                        @if ($loan->is_returned == false && $loan->is_using == false)
                        <button type="submit" style="width: 130px" class="btn btn-danger btn-md mb-1">
                            <i class="fas fa-trash"></i> Batalkan
                        </button>    
                        @endif
                        
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center text-muted" colspan="9">Tidak ada peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $loans ->appends(['items_per_page' =>request()->get('items_per_page')])->links() }}
    </div>
</div>

<script>
    function cancelAlert(event, loanDate, loanAssetName){
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: 'Apakah anda yakin ingin membatalkan peminjaman' + ' ' + loanAssetName ,
            text:  'Untuk ' + loanDate + '?',
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
<script>
    function changeItemsPerPage(itemsPerPage){
        let url = new URL(window.location.href);
        url.searchParams.set('items_per_page', itemsPerPage);
        window.location.href = url.toString();
    }
</script>

@endsection
