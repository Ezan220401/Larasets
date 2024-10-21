@extends('layouts.formMaster')

@section('titlePage', 'Edit Kategori Pengguna')
@section('title', 'Edit Kategori Pengguna')

@section('content')
@if(auth()->user()->group_id == 2)
                        <div class="container mt-5 mb-5">

                        <form action="{{ route('user_category.update', $category->group_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="group_name"><b>Nama Kategori</b></label>
                                <input type="text" class="form-control @error('group_name') is-invalid @enderror" 
                                    name="group_name" id="group_name" value="{{ old('group_name', $category->group_name) }}" required>
                                <!-- error message -->
                                @error('group_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="group_desc"><b>Deskripsikan Kategori</b></label>
                                <input type="text" class="form-control @error('group_desc') is-invalid @enderror" 
                                    name="group_desc" id="group_desc" value="{{ old('group_desc', $category->group_desc) }}" required>
                                <!-- error message -->
                                @error('group_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>
                            
                            <button type="submit" onclick="generateCode()" class="btn btn-md btn-primary">SAVE</button>
                            <a href="{{ route('user.information') }}" class="btn btn-md btn-secondary">BACK</a>

                        </form>

                        </div>
                        @else
                            <div class="container mt-5 mb-5">
                                <div class="alert alert-danger" role="alert">
                                    Hanya Akademik yang dapat mengedit kategori pengguna.
                                </div>
                            </div>
                        @endif
    @endsection
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- include summernote js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</body>

</html>