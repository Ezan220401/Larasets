<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container shadow p-4 mb-4 bg-white mt-5 col-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="text-center mt-3">
                    <img style="width: 20%;" src="{{ asset('img/logo_utb.png') }}" alt="Logo UTB">
                    <h3 class="mt-3"><b>LARASET</b></h3>
                </div>
                @if(session('error'))
                <div class="alert alert-danger mt-3">
                    <b>Oops!</b> {{ session('error') }}
                </div>
                @endif
                <form action="{{ route('action_login') }}" method="post" class="mt-3">
                @csrf
                    <div class="form-group">
                        <label for="user_email">Username</label>
                        <input type="email" id="user_email" name="user_email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                    <hr>
                    <p class="text-center">Lupa password? Silahkan buat permintaan untuk <a href="user/forget/password">mengganti password!</a> sekarang!</p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
