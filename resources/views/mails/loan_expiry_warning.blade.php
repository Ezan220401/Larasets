<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder | Pengingat Batas</title>
</head>
<body>
    <div class="container-fluid">
        <p>Peminjaman Anda akan habis dalam 30 menit.</p>
        <p>Detail Peminjaman:</p>
        <p>Tanggal Peminjaman: {{ $loanDate }}</p>
        <p>Lama Peminjaman: {{ $loanLength }}</p>
    </div>
    
</body>
</html>