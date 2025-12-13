<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pekerja - Street Coffee Mocktail</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('images/img1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: 0;
        }
        .form-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 245, 230, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(90, 64, 58, 0.4);
            width: 350px;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-out {
            opacity: 0 !important;
            transform: translateY(-20px) !important;
            transition: all 0s ease-in !important;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #b89f86;
            background-color: #fffaf3;
            text-align: center;
        }
        button {
            width: 95%;
            background-color: #7B5E57;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background-color: #5a403a; }
        .logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <img src="{{ asset('images/scm_logo.png') }}" alt="Logo" class="logo">
        <h2>Login Pekerja</h2>
         

        <form method="POST" action="/login/pekerja">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" required><br>
            <input type="text" name="kode_pegawai" placeholder="Kode Pegawai (mis. SCMXXXXXX)" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun pekerja? <a href="/register/pekerja" class="switch-link">Daftar disini </a></p>

        <p style="margin-top: 10px;">
            <a href="{{ route('login') }}" class="switch-link" style="color: #7B5E57; text-decoration: none;">
                 ← Kembali
            </a>
        </p>




        @if (session('error'))
            <script>alert("{{ session('error') }}");</script>
        @endif
        @if (session('success'))
            <script>alert("{{ session('success') }}");</script>
        @endif
    </div>

    <script>
        document.querySelectorAll('.switch-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = this.href;
            });
        });
    </script>
</body>
</html>
