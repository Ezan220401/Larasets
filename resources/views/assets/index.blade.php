@extends('layouts.master')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<style>
    @media (min-width: 577px) {
    #type {
        display: none;
    }
}
/* Phone */
@media (max-width: 576px) {
    #span {
        display: block;
    }

    tr th:nth-child(1),
    tr td:nth-child(1),
    tr th:nth-child(3),
    tr td:nth-child(3),
    tr th:nth-child(5),
    tr td:nth-child(5) {
        display: none;
    }

    .table-empty {
        width: 100%;
    }
}
</style>
@section('titlePage', 'Data Aset')
@section('title', 'Data Aset')

@section('content')
    <!-- Add -->
    <div class="d-flex form-inline justify-content-between">
        <div>
            @if (auth()->user()->group_id == 1)
            <a href="{{ route('asset.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Data Baru
            </a> 
            @endif
        </div>

        <!-- Search -->
        <br>
        <form class="form-inline mt-4" method="get" action="{{ route('asset.index') }}">
            <div class="input-group">
                <select name="categories" id="categoryFilter" class="form-control border border-info">
                    <option value="all">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}" {{ request('categories') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" class="form-control ml-2 border border-info" id="search" placeholder="Kata yang dicari"
                       value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
    <div class="form-inline w-100">
            <label for="itemsPerPage">Jumlah data perhalaman</label>
            <select name="itemsPerPage" id="itemsPerPage" class="form-control border border-secondary ml-2 pr-5" onchange="changeItemsPerPage(this.value)">
                <option value="10" {{ request()->get('items_per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="15" {{ request()->get('items_per_page') == 15 ? 'selected' : '' }}>15</option>
                <option value="20" {{ request()->get('items_per_page') == 20 ? 'selected' : '' }}>20</option>
            </select>
        </div>   
<table class="table table-responsive table-bordered table-hover" style="max-width: 100%;" id="itemsTable">
    <thead class="bg-primary text-white">
        <tr>
            <th class="text-center">Kategori</th>
            <th class="text-center">Nama Aset</th>
            <th class="text-center">Tipe</th>
            <th class="text-center">Deskripsi</th>
            <th class="text-center">Waktu Masuk</th>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($assets as $asset)
            <tr>
                <td class="text-center">
                    @foreach($categories as $category)
                        @if($asset->category_id == $category->category_id)
                            {{ $category->category_name }}
                        @endif
                    @endforeach
                </td>
                <td class="text-center">{{ $asset->asset_name }} <span id="type">tipe {{ $asset->asset_type }}</span></td>
                <td class="text-center">{{ $asset->asset_type }}</td>
                <td class="text-center">{{ $asset->asset_desc }}. <br><i class="fa-solid fa-map-pin" style="color: #eb0000;"></i> Terletak di <u>{{ $asset->asset_position }}</u></td>
                <td class="text-center">{{ $asset->asset_date_of_entry }}</td>
                <td class="text-center">{{ $asset->asset_quantity}}</td>
                <td class="text-center">
                    <form onsubmit = "return deleteAlert(event, '{{ $asset->asset_name }}', '{{ $asset->asset_type }}');" action="{{ route('asset.destroy', $asset->asset_id) }}" method="POST">
                        <a href="{{ route('asset.show', $asset->asset_id) }}" style="width: 90px" class="btn btn-info btn-md mb-2">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                        @if(auth()->user()->group_id == 1)
                            <a href="{{ route('asset.edit', $asset->asset_id) }}" style="width: 90px" class="btn btn-warning btn-md mb-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="width: 90px"  class="btn btn-danger btn-md mb-2">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        @endif
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td></td>
                <td class="text-center text-muted" style="width: 100%;">Data asset tidak tersedia</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-4">
    {{ $assets->appends(['items_per_page' => request()->get('items_per_page')])->links() }}
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
<script>
    function changeItemsPerPage(itemsPerPage){
        var url = new URL(window.location.href);
        url.searchParams.set('items_per_page', itemsPerPage);
        window.location.href = url.toString();
    }
</script>
<script>
    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, function(match) {
            const escapeMap = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return escapeMap[match];
        });
    }

    function deleteAlert(event, assetName, assetType){
        event.preventDefault();
        const form = event.target;
        const safeName = escapeHtml(assetName); 
        const safeType = escapeHtml(assetType); 

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus data "' + safeName + ' (' + safeType + ')"?',
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
