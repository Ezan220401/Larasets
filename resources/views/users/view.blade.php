@extends('layouts.master')

@section('titlePage', 'Informasi Pengguna')
@section('title')
 Info | {{ $user->user_name }}
@endsection

    <style>
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
            border: black 1px;
            border-radius: 5%;
        }
    </style>
@section('content')
    <div class="container card border-0 shadow rounded col-lg-10 mt-3 mb-3">
        <hr>
        <h1 class="bg-info p-3 rounded text-left">{{$user->user_name}}</h1>
        <div class="row mt-4">
            <!-- Kiri -->
            <div class="col-lg-4">
                <div class="text-left">
                    <h3 class="btn btn-md btn-secondary md-2">
                        @foreach($groups as $group)
                            @if($user->group_id == $group->group_id)
                                {{ $group->group_name }}
                            @endif
                        @endforeach
                    </h3>
                </div>
                <hr>
                <div class="image-container">
                    @if ($user->user_photo && Storage::exists('public/' . $user->user_photo))
                        <img src="{{ asset('storage/' . $user->user_photo) }}" alt="{{ $user->user_name }}">
                    @else
                        <img src="{{ asset('img/person.png') }}" alt="Default Image">
                    @endif
                </div>
            </div>

            <!-- Kanan -->
            <div class="col-lg-8">
                <div>
                    <h5 class="text-primary p-1 rounded">Nomor ID</h5>
                    <p class="text-bg-light p-3 rounded">{{$user->user_number_id}}</p>

                    <h5 class="text-primary p-1 rounded">Email</h5>
                    <p class="text-bg-light p-3 rounded">{{$user->user_email}}</p>

                    <h5 class="text-primary p-1 rounded">Telpon</h5>
                    <p class="text-bg-light p-3 rounded">{{$user->user_phone}}</p>
                </div>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                @if ($user->group_id === 7)
                <a href="{{ route('home.index') }}" class="btn btn-md btn-info md-2"><b>Kembali</b></a>
                @else
                <a href="{{ $user->group_id === 7 ? url('/users/students') : url('/users/admins') }}" class="btn btn-md btn-info md-2"><b>Kembali</b></a>
                @endif    
                    
                    @if(auth()->user()->user_name == $user->user_name && auth()->user()->user_id == $user->user_id )
                    <a href="{{ route('user.edit', ['user' => $user->user_id]) }}" class="btn btn-md btn-warning md-2">
                        <i class="fas fa-edit"></i><b>Perbarui Profil</b>
                    </a>
                    @endif
                </div>
        </div>
        
        <hr>
    </div>
@endsection
