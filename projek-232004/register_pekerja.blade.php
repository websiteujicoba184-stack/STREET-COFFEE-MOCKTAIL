<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Pekerja - Street Coffee Mocktail</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('{{ asset('images/img1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #2e2a28;
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
            background: rgba(255, 250, 240, 0.6);
            z-index: 0;
            backdrop-filter: blur(3px);
        }

        .form-container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 245, 230, 0.9);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 0 20px rgba(90, 64, 58, 0.4);
            width: 350px;
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
            transition: all 0.1s ease-in !important;
        }

        .logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(123, 94, 87, 0.4);
        }

        /* ====== GAMBAR PEKERJA POJOK KANAN ====== */
        .worker-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .worker-icon:hover {
            transform: scale(1.1);
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #b89f86;
            text-align: center;
            font-size: 14px;
            background-color: #fffaf3;
        }

        button {
            width: 95%;
            background-color: #7B5E57;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background-color: #5a403a;
        }

        a {
            color: #7B5E57;
            text-decoration: none;
            font-size: 14px;
        }

        h2 {
            margin-bottom: 15px;
            color: #7B5E57;
        }
    </style>
</head>
<body>
    <div class="form-container">
        {{-- Gambar pekerja di pojok kanan atas --}}
        
        <img src="{{ asset('images/scm_logo.png') }}" alt="SCM Logo" class="logo">
        <h2>Daftar Pekerja</h2>

        <form method="POST" action="/register/pekerja">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun pekerja? <a href="/login/pekerja" class="switch-link">Login di sini</a></p>

        {{-- Notifikasi --}}
        @if (session('status'))
            <script>alert("{{ session('status') }}");</script>
        @endif
        @if (session('error'))
            <script>alert("{{ session('error') }}");</script>
        @endif
        @if (session('success'))
            <script>alert("{{ session('success') }}");</script>
        @endif
        @if (session('kode_pegawai'))
            <script>alert("Pendaftaran berhasil!\nKode Pegawai Anda: {{ session('kode_pegawai') }}");</script>
        @endif
    </div>

    <script>
        document.querySelectorAll('.switch-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const container = document.querySelector('.form-container');
                container.classList.add('fade-out');
                setTimeout(() => {
                    window.location.href = this.href;
                }, 100);
            });
        });
    </script>
</body>
</html>
