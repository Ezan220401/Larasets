@extends('layouts.master')

@section('titlePage', 'Informasi Kategori Aset')
@section('title', 'Informasi Kategori Aset')

@section('content')
<div class="container">
    @if(auth()->user()->group_id == 1 && $group->category_id > 7)
    <div>
        <a href="{{ route('asset_categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Kategori Baru
        </a>
    </div>
    @endif
    <h1 class="text-center my-4">Kategori Aset</h1>
    <div class="row justify-content-center">
        @forelse ($groups as $group)
            <div class="col-md-5 bg-secondary m-3 p-3 rounded">
                <h4 class="text-center text-white rounded-lg">{{ $group->category_name }}</h4>
                <div class="bg-primary text-white p-2 rounded mt-2">
                    <h5>Deskripsi</h5>
                </div>
                <p class="bg-info text-dark p-2 rounded mt-2">{{ $group->category_desc }}</p>
                <p class="bg-info text-dark p-2 rounded mt-2">Code: {{ $group->code }}</p>
                <div class="bg-primary text-white p-2 rounded mt-2">
                    <h5>Contoh Aset</h5>
                </div>
                <ul class="bg-info text-black rounded mt-2">
                    @php
                        $counter = 0;
                    @endphp

                    @forelse ($assets as $asset)
                        @if ($asset->category_id == $group->category_id)
                            @if ($counter < 3)
                            <li class="">{{ $asset->asset_name }} {{ $asset->asset_type }}</li>
                                @php
                                    $counter++;
                                @endphp
                            @else
                                @break
                            @endif
                        @endif
                    @empty
                        <li class="list-group-item">Tidak ada aset yang tersedia.</li>
                    @endforelse
                </ul>
                @if (auth()->user()->group_id == 1 && $group->category_id > 7)
                    <form onsubmit = "return deleteAlert(event, '{{ $group->category_name }}' );" action="{{ route('asset_category.destroy', $group->category_id) }}" method="POST">
                        <a href="{{ route('asset_category.edit', $group->category_id) }}" class="btn btn-warning btn-md mb-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-md mb-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <h4 class="text-center">Ada masalah saat memuat data.</h4>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $assets->links() }}
    </div>
</div>

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

    function deleteAlert(event, categoryName) {
        event.preventDefault();
        const form = event.target;
        const safeCategoryName = escapeHtml(categoryName);

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus kategori ' + safeCategoryName + '?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.value) {
                Swal.fire('Berhasil!', 'Data Berhasil dihapus.', 'success').then(() => {
                    form.submit();
                });
            }
        });
    }
</script>
@endsection