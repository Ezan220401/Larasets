@extends('layouts.master')

@section('titlePage', 'Data Mahasiswa dan Dosen')
@section('title', 'Data Mahasiswa dan Dosen')

@section('content')

@if(auth()->user()->user_id == 2)
    <!-- ATambah data -->
    <div class="mb-1">
        <a href="{{ route('user.create') }}" class="btn btn-success btn-md">
            <i class="fas fa-plus"></i> Data Baru
        </a>
    </div>
@endif

<!-- Pencarian -->
<br>
<form class="form-inline mt-1" method="get" action="{{ route('student.index') }}">
    <div class="input-group">
        <input type="text" name="search" class="form-control ml-2 border border-info" id="search" placeholder="Nama atau ID" value="{{ request('search') }}">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary ml-2"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        </div>
    </div>
</form>

<!-- Data pengguna -->
    <div class="form-inline w-100 mt-2">
        <label for="itemsPerPage">Jumlah data per halaman</label>
        <select name="itemsPerPage" id="itemsPerPage" class="form-control border border-secondary ml-2 pr-5" onchange="changeItemsPerPage(this.value)">
            <option value="10" {{ request()->get('items_per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ request()->get('items_per_page') == 15 ? 'selected' : '' }}>15</option>
            <option value="20" {{ request()->get('items_per_page') == 20 ? 'selected' : '' }}>20</option>
        </select>
    </div>   

    <table class="table w-100 table-responsive table-bordered table-hover rounded">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center">Nama</th>
                <th scope="col" class="text-center">Jabatan</th>
                <th scope="col" class="text-center">Kontak</th>
                <th scope="col" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td style="width:70%" class="text-center">{{ $user->user_name }}</td>
                <td class="text-center">
                    @foreach($groups as $group)
                        @if($user->group_id == $group->group_id)
                            {{ $group->group_name }}
                        @endif
                    @endforeach
                </td>
                <td class="text-center">
                    @php
                        $phoneNumber = preg_replace('/^0/', '+62', $user->user_phone);
                    @endphp
                    <a href="https://wa.me/{{ $phoneNumber }}" class="d-inline-block mx-2">
                        <img src="{{ asset('img/whatsapp_icon.png') }}" alt="WhatsApp" style="width: 32px; height: 32px;">
                    </a>
                    <a href="mailto:{{ $user->user_email }}?subject=Hello%20World&body=This%20is%20the%20body%20of%20the%20email." class="d-inline-block mx-2">
                        <img src="{{ asset('img/mail_icon.png') }}" alt="Email" style="width: 32px; height: 32px;">
                    </a>
                </td>
                <td class="text-center">
                    <a href="{{ route('user.view', $user->user_id) }}" class="btn btn-info btn-md mt-2">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if (auth()->check() && auth()->user()->group_id == 2)
                        <form onsubmit="return deleteAlert(event, '{{ $user->user_name }}')" method="POST" class="d-inline" action="{{ route('user.destroy', $user->user_id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-md mt-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td class="text-center text-muted" colspan="4">Data user tidak tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $users->appends(['items_per_page' => request()->get('items_per_page')])->links() }}
    </div>

<script>
    function deleteAlert(event, userName) {
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus data ' + userName + '?',
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
<script>
    function changeItemsPerPage(itemsPerPage){
        var url = new URL(window.location.href);
        url.searchParams.set('items_per_page', itemsPerPage);
        window.location.href = url.toString();
    }
</script>
@endsection
