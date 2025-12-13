<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email | Street Coffee Mocktail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #faf9f7;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 40px;
            background: #fff;
        }
        .btn-coffee {
            background-color: #846445;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
        }
        .btn-coffee:hover {
            background-color: #a97133;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card text-center" style="max-width:500px;">
        <img src="{{ asset('images/scm_logo.png') }}" width="90" class="mb-3" style="border-radius:50%;">
        <h4 class="mb-3">Verifikasi Email Diperlukan</h4>
        <p class="text-muted mb-4">Kami telah mengirimkan link verifikasi ke email Anda.  
        Silakan cek inbox (atau folder spam) untuk mengaktifkan akun.</p>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-coffee">Kirim Ulang Link Verifikasi</button>
        </form>
    </div>
</div>
</body>
</html>
