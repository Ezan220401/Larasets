@extends('layouts.formMaster')

@section('title', 'Edit Data Aset')

@section('content')

                @if(auth()->user()->group_id == 1)
                <div class="container mt-5 mb-5">
                        
                    <form action="{{ route('asset.update', $asset->asset_id) }}" method="POST" enctype="multipart/form-data" onsubmit="getLocation()">
                        @csrf
                        @method('PUT')
                            <div class="form-group">
                                <label for="asset_name"><b>Nama Aset</b></label>
                                <input type="text" class="form-control @error('asset_name') is-invalid @enderror"
                                    name="asset_name" value="{{ old('asset_name', $asset->asset_name) }}" required>
                                @error('asset_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="category_id"><b>Kategori</b></label>
                                <select name="category_id" class="form-control" required>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->category_id }}" {{ $item->category_id == old('category_id', $asset->category_id) ? 'selected' : '' }}>
                                            {{ $item->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_type"><b>Tipe</b></label>
                                <input type="text" class="form-control @error('asset_type') is-invalid @enderror"
                                    name="asset_type" value="{{ old('asset_type', $asset->asset_type) }}" required>
                                @error('asset_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_position"><b>Lokasi</b></label>
                                <input type="text" class="form-control @error('asset_type') is-invalid @enderror"
                                    name="asset_position" value="{{ old('asset_position', $asset->asset_position) }}" required>
                                @error('asset_position')
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
                                    name="maintenance_desc" id="maintenance_desc" value="{{ old('maintenance_desc', $asset->maintenance_desc) }}" required>

                                @error('maintenance_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_desc"><b>Deskripsi</b></label>
                                <input type="text" class="form-control @error('asset_desc') is-invalid @enderror"
                                    name="asset_desc" value="{{ old('asset_desc', $asset->asset_desc) }}" required>
                                @error('asset_desc')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_price"><b>Harga satuan:</b></label>
                                <input type="number" min="1000" class="form-control @error('asset_price') is-invalid @enderror" 
                                    name="asset_price" id="asset_price" value="{{ old('asset_price', $asset->asset_price) }}" required>
                                @error('asset_price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_date_of_entry"><b>Tanggal masuk</b></label>
                                <input type="datetime-local" class="form-control @error('asset_date_of_entry') is-invalid @enderror"
                                    name="asset_date_of_entry" value="{{ old('asset_date_of_entry', $asset->asset_date_of_entry) }}" required>
                                @error('asset_date_of_entry')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="asset_quantity"><b>Jumlah</b></label>
                                <input type="number" min="1" class="form-control @error('asset_quantity') is-invalid @enderror"
                                    name="asset_quantity" value="{{ old('asset_quantity', $asset->asset_quantity) }}" max=500 required>
                                @error('asset_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>     
                            <hr>    
                            
                            <div class="form-group">
                                <label for="receipt_number"><b>Nomor Resi(BKK)</b></label>
                                <input type="number" min="99" minlength="3" class="form-control @error('receipt_number') is-invalid @enderror"
                                    name="receipt_number" value="{{ old('receipt_number', $asset->receipt_number) }}" required>
                                @error('receipt_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>     
                            <hr>    
                            
                            <div class="form-group">
                            <label for="asset_image" class="form-label @error('asset_image') is-invalid @enderror"><b>Masukkan Gambar</b></label>
                            <p class="text-warning badge badge-secondary p-2">Kosongkan bila tidak diganti</p>
                            @if ($asset->asset_image)
                                <br><img class="img-preview img-fluid mb-3 col-sm-5" style="max-width:20%" src="{{ asset('storage/' . $asset->asset_image) }}" alt="{{ $asset->asset_name }}">
                            @else
                                <br><img class="img-preview img-fluid mb-3 col-sm-5" id="preview" style="display: none; max-width:20%">
                            @endif
                            <input class="form-control" type="file" id="asset_image" name="asset_image" onchange="previewImage();" placeholder="Harap masukkan gambar">
                            @error('asset_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>


                            <input type="hidden" id="asset_code" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" readonly required>
                            
                            <button type="submit" class="btn btn-md btn-primary">PERBARUI</button>
                            <a href="{{ route('asset.index') }}" class="btn btn-md btn-secondary">KEMBALI</a>
                        </form>
                    </div>
                    @else
                        <div class="container mt-5 mb-5">
                            <div class="alert alert-danger" role="alert">
                                Hanya Koordinator Aset yang dapat mengedit data aset
                            </div>
                        </div>
                    @endif
    @endsection
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
