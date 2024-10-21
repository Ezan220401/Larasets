@extends('layouts.formMaster')

@section('title', 'Tambah Data Aset')

@section('content')
    @if(auth()->user()->group_id == 1)
    <div class="container mt-5 mb-5">

        <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="category_id"><b>Kategori</b></label>
                <select name="category_id" class="form-control" required>
                    @foreach ( $category as $item)
                        <option value="{{ $item->category_id }}">{{ $item->category_name}}</option>    
                    @endforeach                    
                </select>
            </div>
            <hr>

            <div class="form-group">
                <label for="asset_name"><b>Nama Aset</b></label>
                <input type="text" class="form-control @error('asset_name') is-invalid @enderror" 
                    name="asset_name" id="asset_name" value="{{ old('asset_name') }}" required>

                <!-- error message untuk name -->
                @error('asset_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>

            <div class="form-group">
                <label for="asset_type"><b>Tipe atau Merek Aset</b></label>
                <input type="text" class="form-control @error('asset_type') is-invalid @enderror"
                    name="asset_type" value="{{ old('asset_type') }}" id="asset_type" required>

                @error('asset_type')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>
            <div class="form-group">
                <label for="position"><b>Lokasi</b></label>
                <div class="form-group">
                    <select style="width: 200px;" id="tower" name="tower" class="form-control" required>
                        <option value="A" selected>Gedung A</option>
                        <option value="B">Gedung B</option>
                    </select>
                    <br>                   
                    <p>Lantai:  <input value="{{ old('floor') }}" type="number" name="floor" id="floor"></p>
                    <p>Ruangan: <input value= "{{ old('room') }}" type="text" name="room" id="room"></p>
                    
                    <input name="asset_position" id="asset_position" style="display: none;">
                </div>
                @error('asset_position')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>
            <div class="form-group">
                <label for="asset_desc"><b>Deskripsi</b></label>
                <input type="text" class="form-control @error('asset_desc') is-invalid @enderror"
                    name="asset_desc" value="{{ old('asset_desc') }}" required>

                @error('asset_desc')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>
            <div class="form-group">
                <label for="asset_date_of_entry"><b>Tanggal masuk</b></label>
                <input type="datetime-local" class="form-control @error('asset_date_of_entry') is-invalid @enderror" 
                    name="asset_date_of_entry" id="asset_date_of_entry" value="{{ old('asset_date_of_entry') }}" required>

                @error('asset_date_of_entry')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>

            <div class="form-group">
                <label for="maintenance_desc"><b>Deskripsikan Aturan Maintenace</b></label>
                <input type="text" class="form-control @error('maintenance_desc') is-invalid @enderror" 
                    placeholder="Misal: Setiap sebulan sekali periksa oli mesin"
                    name="maintenance_desc" id="maintenance_desc" value="{{ old('maintenance_desc') }}" required>

                @error('maintenance_desc')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>

            <div class="form-group">
                <label for="receipt_number"><b>Nomor Resi (BKK)</b></label>
                <input type="number" class="form-control @error('receipt_number') is-invalid @enderror" 
                    name="receipt_number" id="receipt_number" value="{{ old('receipt_number') }}" required>

                @error('receipt_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>
            
            <div class="form-group">
                <label for="asset_quantity"><b>Jumlah</b></label>
                <input type="number" min="0" class="form-control @error('asset_quantity') is-invalid @enderror" 
                    name="asset_quantity" id="asset_quantity" value="{{ old('asset_quantity') }}" max=500 required>

                @error('asset_quantity')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <hr>
            <div class="form-group">
                <label for="asset_price"><b>Harga satuan:</b></label>
                <input type="number" min="0" class="form-control @error('asset_price') is-invalid @enderror" 
                    name="asset_price" id="asset_price" value="{{ old('asset_price') }}" required>

                @error('asset_price')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
    
            </div>
            <hr>
            <div class="mb-3">
                <label for="asset_image" class="form-label @error('asset_image') is-invalid @enderror"><b>Masukkan Gambar</b></label>
                <img class="img-preview img-fluid mb-3 col-sm-5" id="preview" style="display: none; max-width:25%">
                <input class="form-control" type="file" id="asset_image" name="asset_image" onchange="previewImage()"
                placeholder="Harap masukkan gambar aset" required>
                @error('asset_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <input type="text" style="display: none;" id="asset_code" name="asset_code" value="{{ old('asset_code') }}" readonly required>
            
            <button type="submit" onclick="generateCode()" class="btn btn-md btn-primary">SIMPAN</button>
            <a href="{{ route('asset.index') }}" class="btn btn-md btn-secondary">KEMBALI</a>

        </form>

        </div>
        @else
            <div class="container mt-5 mb-5">
                <div class="alert alert-danger" role="alert">
                    Hanya Koordinator Aset yang dapat menambah data aset.
                </div>
            </div>
        @endif
    </div>
    @endsection
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- include summernote js -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        let categorySelect = document.querySelector('select[name="category_id"]');

        categorySelect.addEventListener("change", function() {
            let categoryId = categorySelect.value;

            // Cari objek yang memiliki category_id sesuai dengan yang dipilih
            let selectedCategory = {!! json_encode($category) !!}.find(function(category) {
                return category.category_id == categoryId;
            });

            // Jika kategori yang dipilih ditemukan, dapatkan category_name-nya
            if (selectedCategory) {
                let categoryName = selectedCategory.category_name;
                console.log("Selected category_name:", categoryName);

                // Gunakan categoryName sesuai kebutuhan di sini
            } else {
                console.error("Category not found for category_id:", categoryId);
            }
        }); 
    });

    function generateCode() {
        let tower = document.getElementById('tower').value;
        let floor = document.getElementById('floor').value;
        let room = document.getElementById('room').value;

        let location = 'Gedung ' + tower + ', Ruangan ' + room + ' di Lantai ' + floor;
        document.getElementById('asset_position').value = location;

        let code = room + tower + floor;

        document.getElementById('asset_code').value = code;

        console.log("Generated asset code:", code); // Logging untuk memastikan kode aset dihasilkan dengan benar
    }

    </script>
    <script>
        $(document).ready(function() {
            $('#asset_image').change(function() {
                previewImage(this);
            });
        });

        function previewImage(input) {
            var preview = $('#preview')[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
    preview.src = e.target.result;
    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>