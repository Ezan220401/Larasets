@extends('layouts.formMaster')

@section('title', 'Edit Data Diri')

@section('content')
                    @if(auth()->user()->user_name == $userName && auth()->user()->user_id == $userId )
                        <div class="container mt-5 mb-5">

                        <form action="{{ route('user.update', ['user' => auth()->user()->user_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="user_name"><b>Nama Anda</b></label>
                                <input type="text" class="form-control @error('user_name') is-invalid @enderror" 
                                    name="user_name" id="user_name" value="{{ old('user_name', $user->user_name) }}" required>

                                <!-- error message -->
                                @error('user_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="user_number_id"><b>Nomor Induk</b></label>
                                <input type="number" class="form-control @error('user_number_id') is-invalid @enderror" 
                                    name="user_number_id" id="user_number_id" value="{{ old('user_number_id', $user->user_number_id) }}" required readonly>

                                <!-- error message -->
                                @error('user_number_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="user_phone"><b>Nomor Whatsapp/Telepon</b></label>
                                <input type="number" class="form-control @error('user_phone') is-invalid @enderror" 
                                    name="user_phone" id="user_phone" placeholder="Harap mulai dengan kode negara bukan 0 (misal: 6289....)" 
                                    value="{{ old('user_phone', $user->user_phone) }}" required>

                                <!-- error message -->
                                @error('user_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="user_email"><b>Email Pengguna</b></label>
                                <input type="email" class="form-control @error('user_email') is-invalid @enderror" 
                                    name="user_email" id="user_email" value="{{ old('user_email', $user->user_email) }}" required>

                                <!-- error message -->
                                @error('user_email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="mb-3">
                                <label for="user_photo" class="form-label @error('user_photo') is-invalid @enderror"><b>Gambar Profil</b></label>
                                <img class="img-preview img-fluid mb-3 col-sm-5" id="preview" style="display: none;">
                                <input class="form-control" type="file" id="user_photo" name="user_photo" onchange="previewImage()">
                                @error('user_photo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit"  class="btn btn-md btn-primary">PERBARUI</button>
                            <a href="{{ route('home.index') }}" class="btn btn-md btn-secondary">KEMBALI</a>

                        </form>

                        </div>
                        @else
                            <div class="container mt-5 mb-5">
                                <div class="alert alert-danger" role="alert">
                                    <p>Hanya {{ $user->user_name }} yang dapat mengedit datanya.</p>
                                </div>
                            </div>
                        @endif
    @endsection

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#user_photo').change(function() {
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
                    preview.style.maxWidth = '200px';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>