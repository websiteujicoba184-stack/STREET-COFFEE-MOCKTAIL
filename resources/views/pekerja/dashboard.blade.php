<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir - Street Coffee Mocktail</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f5f0;
            color: #3e2723;
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #7b5e57;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            transition: 0.3s;
            display: block;
        }

        .sidebar a:hover {
            background-color: #5a403a;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            position: relative;
        }

        /* Topbar */
        .topbar {
            background-color: #d7ccc8;
            color: #3e2723;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-info span {
            display: block;
            font-size: 14px;
        }

        .logout-btn {
            background-color: #7b5e57;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background-color: #5a403a;
        }

        /* Card */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #7b5e57;
        }

        .card p {
            color: #5d4037;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>☕ SCM Admin</h2>
        <a href="#">📦 Stok Barang</a>
        <a href="#">🧾 Pesanan Masuk</a>
        <a href="#">💰 Total Penjualan</a>
        <a href="#">💳 Metode Pembayaran</a>
        <a href="#">⚙️ Kelola Akun</a>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn" style="width: 100%; margin-top: 20px;">🚪 Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="topbar">
            <h2>Dashboard Kasir</h2>
            <div class="user-info">
                <span><strong>{{ Auth::user()->name }}</strong></span>
                <span>Kode Pegawai: <strong>{{ Auth::user()->kode_pegawai }}</strong></span>
            </div>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>📦 Stok Barang</h3>
                <p>Jumlah menu tersedia: <strong>24</strong></p>
            </div>
            <div class="card">
                <h3>🧾 Pesanan Hari Ini</h3>
                <p>Total pesanan masuk: <strong>15</strong></p>
            </div>
            <div class="card">
                <h3>💰 Total Penjualan</h3>
                <p>Rp <strong>2.750.000</strong></p>
            </div>
            <div class="card">
                <h3>💳 Metode Pembayaran</h3>
                <p>Tunai, QRIS, Transfer</p>
            </div>
        </div>
    </div>

</body>
</html>
