@extends('admin.layout')

@section('content')
<div class="container py-4">
    <!-- Judul Halaman -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark">Dashboard Admin</h1>
        <p class="text-muted">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> 👋</p>
        <hr class="w-50 mx-auto" style="border-color: #c48a47;">
    </div>

     <div>
            <a href="{{ route('kasir.index') }}" class="btn btn-warning">
                💰 Menu Kasir
             </a>
        </div>

    <!-- Statistik Utama -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; color: #c48a47;"></i>
                </div>
                <h5 class="fw-bold">Total Produk</h5>
                <h3 class="text-dark fw-bold">{{ $total_produk }}</h3>
                <p class="text-muted">Produk aktif di menu</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-cart-check" style="font-size: 2.5rem; color: #ffb100;"></i>
                </div>
                <h5 class="fw-bold">Pesanan Pending</h5>
                <h3 class="text-dark fw-bold">{{ $pesanan_pending }}</h3>
                <p class="text-muted">Menunggu konfirmasi</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-cash-stack" style="font-size: 2.5rem; color: #28a745;"></i>
                </div>
                <h5 class="fw-bold">Total Penjualan</h5>
                <h3 class="text-dark fw-bold">{{ $total_produk }}</h3>
                <p class="text-muted">Penjualan bulan ini</p>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi Cepat -->

    <canvas id="salesChart" height="120"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
        datasets: [{
            label: 'Penjualan (Rp)',
            data: [1200000, 1500000, 900000, 1800000, 2200000],
            backgroundColor: '#c48a47'
        }]
    },
    options: { responsive: true }
});
</script>

    <div class="row justify-content-center">
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.produk') }}" class="btn w-100 py-3 btn-coffee shadow-sm">
                <i class="bi bi-box2-heart me-2"></i> Kelola Produk
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.pesanan') }}" class="btn w-100 py-3 btn-outline-dark shadow-sm">
                <i class="bi bi-receipt-cutoff me-2"></i> Lihat Pesanan
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.metode') }}" class="btn w-100 py-3 btn-outline-dark shadow-sm">
                <i class="bi bi-credit-card me-2"></i> Metode Pembayaran
            </a>
        </div>
    </div>
</div>

<!-- Tambahan ikon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
