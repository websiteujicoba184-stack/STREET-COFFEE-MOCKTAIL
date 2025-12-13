<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Berhasil | Street Coffee Mocktail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #faf9f7;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }
        .btn-coffee {
            background-color: #846445;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-coffee:hover {
            background-color: #a97133;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card">
            <img src="{{ asset('images/scm_logo.png') }}" width="90" class="mb-3" style="border-radius:50%;">
            <h2 class="text-success mb-3">Verifikasi Berhasil!</h2>
            <p class="text-muted">Akun Anda telah aktif. Terima kasih telah bergabung dengan <b>Street Coffee Mocktail ☕</b></p>
            <a href="{{ route('login') }}" class="btn-coffee mt-3">Login Sekarang</a>
        </div>
    </div>
</body>
</html>
