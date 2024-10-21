<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
</head>
<body>
        <h3>{{$mailData['title']}}</h3>
        <p>Kepada {{$mailData['name']}}, permintaan anda telah disetujui untuk mengubah password anda. Silahkan segera ubah password anda menggunakan otp yang ada dibawah ini untuk mencegah pengguna lain menggunakan akun anda dan silahkan masuk melalui link berikut: <a href="http://laraset.site/change_password">Ganti password</a>
        </p>  
        
        <!-- OTP -->
        <h1>{{$mailData['otp']}}</h1> 

        <p>Sekian dari kami, terimakasih atas partisipasinya dan selamat bergabung.</p>
</body>
</html>