<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Street Coffee Mocktail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 🌅 Background full page + overlay gelap */
        body {
            background: url('{{ asset("images/img1.jpg") }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0); /* lapisan gelap */
            z-index: -1;
        }

        /* 🌈 Navbar Style */
        .navbar-custom {
            background: rgba(5, 3, 3, 0); /* warna coklat tapi 35% transparan */
            backdrop-filter: blur(12px);          /* efek blur kaca */
            -webkit-backdrop-filter: blur(12px);  /* untuk Safari */
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }


        .navbar-brand {
            display: flex;
            align-items: center;
            color: #fff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .navbar-brand img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .btn-coffee {
            background-color: #f84141ff;
            color: #fff;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-coffee:hover {
            background-color: #a97133;
            transform: scale(1.05);
        }

        footer {
            color: #ddd;
            font-size: 14px;
        }

        /* 🌫️ Glass effect untuk card di halaman admin */
        .glass-card {
            background: rgba(245, 245, 245, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.4);
        }

        .card-title {
            color: #fff;
            text-shadow: 0 2px 6px rgba(155, 151, 151, 0.8);
        }

        .card-text {
            color: #f0f0f0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('images/logo1.jpg') }}" alt="Logo SCM">
        Street Coffee Mocktail | Admin
    </a>
    <div class="d-flex">
        <form action="{{ route('logout') }}" method="POST" class="m-0 me-2">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <a href="{{ route('admin.produk') }}" class="btn btn-coffee">Kelola Produk</a>
        <a href="{{ route('admin.report') }}" class="btn btn-info ms-2">
    📄 Report
</a>

    </div>
  </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

<footer class="text-center mt-4 mb-3">
    <p>© {{ date('Y') }} Street Coffee Mocktail | Admin Panel</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
