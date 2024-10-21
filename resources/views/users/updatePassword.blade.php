<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset | Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- include summernote css -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">

                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <div class="container mt-5 mb-5">

                        <form action="{{ route('resetPassword')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                        
                            <div class="form-group">
                                <label for="email"><b>Email anda</b></label>
                                <p class="label label-warning text-black"></p>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" id="email" value="{{ old('email') }}" required >

                                <!-- error message untuk email -->
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="otp"><b>OTP</b></label>
                                <p class="label label-warning text-black">Gunakan OTP terbaru di email anda</p>
                                <input type="text" class="form-control @error('otp') is-invalid @enderror" 
                                    name="otp" id="otp" value="{{ old('otp') }}" required >

                                <!-- error message untuk otp -->
                                @error('otp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="password"><b>Password</b></label>
                                <p class="label label-warning text-black">Minimal 8 karakter, dengan kombinasi angka, huruf dan tanda baca!</p>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" id="password" value="{{ old('password') }}" required >

                                <!-- error message untuk password -->
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <div class="form-group">
                                <label for="password2"><b>Ulangi Password</b></label>
                                <input type="password" class="form-control @error('password2') is-invalid @enderror" 
                                    name="password2" id="password2" value="{{ old('password2') }}" required>

                                <!-- error message untuk password2 -->
                                @error('password2')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <hr>

                            <button type="submit" class="btn btn-lg btn-primary">PERBARUI</button>
                            <a href="{{ route('login') }}" class="btn btn-lg btn-secondary">KEMBALI</a>

                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</body>

</html>