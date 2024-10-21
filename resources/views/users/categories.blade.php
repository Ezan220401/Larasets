@extends('layouts.master')

@section('titlePage', 'Informasi Kategori Jabatan')
@section('title', 'Informasi Kategori Jabatan')

@section('content')
<div class="container">
    @if(auth()->user()->group_id == 2)
    <div>
        <a href="{{ route('user_categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Kategori Baru
        </a>
    </div>
    @endif
    <h1 class="text-center my-4">Kategori Jabatan</h1>
    <div class="row justify-content-center">
        @forelse ($groups as $group)
            <div class="col-md-5 bg-secondary m-3 p-3 rounded">
                <h4 class="text-center text-white rounded-lg">{{ $group->group_name }}</h4>
                <div class="bg-primary text-white p-2 rounded mt-2">
                    <h5>Deskripsi</h5>
                </div>
                <p class="bg-info text-dark p-2 rounded mt-2">{{ $group->group_desc }}</p>
                <div class="bg-primary text-white p-2 rounded mt-2">
                    <h5>Contoh Pemilik Jabatan</h5>
                </div>
                <ul class="bg-info rounded mt-2">
                    @php
                        $counter = 0;
                    @endphp

                    @forelse ($users as $user)
                        @if ($user->group_id == $group->group_id)
                            @if ($counter < 5)
                            <li>{{ $user->user_name }}</li>
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
                @if (auth()->user()->group_id == 2 && $group->group_id > 11 )
                    <form onsubmit="return deleteAlert(event, '{{ $group->group_name }}' );" action="{{ route('user_category.destroy', $group->group_id) }}" method="POST">
                        <a href="{{ route('user_category.edit', $group->group_id) }}" class="btn btn-warning btn-md mb-2">
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
</div>
<script>
    function deleteAlert(event, categoryName) {
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus kategori ' + categoryName + '?',
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