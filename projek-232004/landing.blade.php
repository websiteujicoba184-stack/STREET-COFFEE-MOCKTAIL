<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Street Coffee Mocktail | Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #777164ff;
            color: #2c2c2c;
        }

        /* 🔥 Hero Section */
        .hero {
            background: linear-gradient(rgba(50, 30, 20, 0.7), rgba(50, 30, 20, 0.7)), 
            color: white;
            text-align: center;
            padding: 120px 20px;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .hero p {
            font-size: 18px;
            margin-top: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: #beb8b2ff;
        }

        .hero .btn-coffee {
            background-color: #846445ff;
            border: none;
            color: white;
            padding: 12px 28px;
            border-radius: 30px;
            margin-top: 25px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .hero .btn-coffee:hover {
            background-color: #a97133;
            transform: scale(1.05);
        }

        /* 🧋 Tentang Kami */
        .about {
            padding: 80px 20px;
            background-color: #777164ff;
        }

        .about img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .about-text h2 {
            color: #c2af9bff;
            font-weight: 600;
        }

        .about-text p {
            color: #beb8b2ff;
            font-size: 16px;
            line-height: 1.8;
        }

        /* ⭐ Menu Populer */
        .menu {
            padding: 80px 20px;
            background-color: #777164ff;
        }

        .menu h2 {
            text-align: center;
            color: #beb8b2ff;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .card {
            border: none;
            border-radius: 10px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card img {
            border-radius: 10px 10px 0 0;
            height: 180px;
            object-fit: cover;
        }

        /* 🧾 Footer */
        footer {
            background-color: #302c28ff;
            color: #ccc;
            text-align: center;
            padding: 25px 10px;
            margin-top: 50px;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- 🔥 HERO SECTION -->
    <section class="hero">
        <img src="{{ asset('images/scm_logo.png') }}" alt="Logo Street Coffee Mocktail" width="100" class="mb-3" style="border-radius:50%; border:2px solid white;">
        <h1>Street Coffee Mocktail</h1>
        <p>Rasakan sensasi perpaduan kopi dan mocktail dengan cita rasa bintang lima, harga kaki lima!  
        Kami hadir untuk menemani setiap momen santaimu ☕✨</p>
        <a href="{{ route('login') }}" class="btn btn-coffee me-2">Login</a>
        <a href="{{ route('register') }}" class="btn btn-coffee me-2">register</a>
    </section>

    <!-- 🧋 TENTANG KAMI -->
    <section class="about container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="{{ asset('images/store.jpg') }}" alt="Toko Street Coffee Mocktail">
            </div>
            <div class="col-md-6 about-text">
                <h2>Tentang Kami</h2>
                <p>
                    Street Coffee Mocktail berdiri dengan semangat menghadirkan minuman kopi dan non-kopi yang unik, segar, dan berkualitas tinggi.  
                    Berlokasi di <strong>perempatan Toddopuli Raya, depan Jank Jank</strong>, kami menawarkan berbagai varian minuman yang cocok untuk semua kalangan.  
                    Dengan slogan <em>“Mau kocok sendiri atau dikocokin?”</em> 😄, kami menghadirkan pengalaman minum yang menyenangkan dan tak terlupakan.
                </p>
            </div>
        </div>
    </section>

    <!-- ⭐ MENU POPULER -->
    <section class="menu">
        <div class="container">
            <h2>Menu Populer Kami</h2>
            <div class="row justify-content-center">
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset('images/street_eskobar.jpg') }}" class="card-img-top" alt="Es Kobar">
                        <div class="card-body text-center">
                            <h5 class="card-title">Es Kobar</h5>
                            <p class="text-muted">Kopi dan coklat berpadu dalam kesegaran yang khas!</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset('images/street_manggoda_jabe.jpg') }}" class="card-img-top" alt="Mango Majito">
                        <div class="card-body text-center">
                            <h5 class="card-title">Manggoda</h5>
                            <p class="text-muted">Perpaduan segar mangga dan soda, bikin nagih!</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ asset('images/street_kepo_tamarind.jpg') }}" class="card-img-top" alt="Kepo Tamarind">
                        <div class="card-body text-center">
                            <h5 class="card-title">Kepo Tamarind</h5>
                            <p class="text-muted">Rasa asam manis yang menyegarkan hari-harimu 🍹</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 🧾 FOOTER -->
    <footer>
        <p>© {{ date('Y') }} Street Coffee Mocktail | All Rights Reserved</p>
    </footer>

</body>
</html>
