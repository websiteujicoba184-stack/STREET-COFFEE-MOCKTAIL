<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Street Coffee Mocktail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f3f3f3;
            font-family: 'Poppins', sans-serif;
        }

        /* 🌈 Navbar Style */
        .navbar-custom {
            background-color: #846445ff; /* mirip form login */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
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
            background-color: #302c28ff;
            color: #fff;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-coffee:hover {
            background-color: #a97133;
            transform: scale(1.05);
        }

        footer {
            color: #888;
            font-size: 14px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card h3 {
            color: #2c2f33;
        }

        .card p {
            color: #666;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('images/scm_logo.png') }}" alt="Logo SCM">
        Street Coffee Mocktail
    </a>
    <div class="d-flex">
        <form action="{{ route('logout') }}" method="POST" class="m-0 me-2">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
        <a href="{{ route('admin.produk') }}" class="btn btn-coffee">Produk</a>
    </div>
  </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

<footer class="text-center mt-4">
    <p>© {{ date('Y') }} Street Coffee Mocktail | Admin Panel</p>
</footer>

</body>
</html>
