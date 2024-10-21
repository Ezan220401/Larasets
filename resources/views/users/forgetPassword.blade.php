<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lupa Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container"><br>
        <div class="col-md-4 col-md-offset-4">
            <div class="clo-md-9 text-center">
                <img style="width: 20%;" src="{{ asset('img/logo_utb.png') }}" alt="">
                <h3><b>Lupa Password?<b></h3>
            </div>
            @if(session('error'))
            <div class="alert alert-danger">
                <b>Opps!</b> {{session('error')}}
            </div>
            @endif
            
            <form action="{{ route('request_password_reset') }}" method="post">
            @csrf
                <div class="form-group">
                    <label>Verifikasi Email</label>
                    <input type="email" name="user_email" class="form-control" placeholder="Email" required="">

                    @error('user_email')
                        <div class="invalid-feedback text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block">Kirim Verifikasi</button>
                <hr>
                <p class="text-center">Sudah ingat kembali?<br>Silahkan kembali <a href="/">login</a></p>
            </form>
        </div>
    </div>
</body>
</html>