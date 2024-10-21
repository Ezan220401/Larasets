@extends('layouts.formMaster')

@section('titlePage', 'Edit Kategori Aset')
@section('title', 'Edit Kategori Aset')

@section('content')
@if(auth()->user()->group_id == 1)
                        <div class="container mt-5 mb-5">

                        <form action="{{ route('asset_category.update', $category->category_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="category_name"><b>Nama Kategori</b></label>
                                <input type="text" class="form-control @error('category_name') is-invalid @enderror" 
                                    name="category_name" id="category_name" value="{{ old('category_name', $category->category_name) }}" required>
                                <!-- error message -->
                                @error('category_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="category_desc"><b>Deskripsikan Kategori</b></label>
                                <input type="text" class="form-control @error('category_desc') is-invalid @enderror" 
                                    name="category_desc" id="category_desc" value="{{ old('category_desc', $category->category_desc) }}" required>
                                <!-- error message -->
                                @error('category_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="category_code"><b>Kode Kategori</b></label>
                                <input type="number" value="{{ old('category_name', $category->code) }}" class="form-control" id="category_code" name="category_code" step="0.01" required>
                                <!-- error message -->
                                @error('category_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>
                            
                            <button type="submit" onclick="generateCode()" class="btn btn-md btn-primary">SAVE</button>
                            <a href="{{ route('asset.information') }}" class="btn btn-md btn-secondary">BACK</a>

                        </form>

                        </div>
                        @else
                            <div class="container mt-5 mb-5">
                                <div class="alert alert-danger" role="alert">
                                    Hanya Koordinator Aset yang dapat mengedit kategori aset.
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