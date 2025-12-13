@extends('admin.layout')

@section('content')
<div class="py-4">

    {{-- Judul Halaman --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold text-white" style="text-shadow:0 3px 10px rgba(0,0,0,0.8);">
            Dashboard Admin
        </h1>
        <p class="text-light">
            Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> 👋
        </p>
        <hr class="w-50 mx-auto" style="border-color: #c48a47;">
    </div>

    {{-- Tombol Menu Kasir --}}
    <div class="mb-4">
        <a href="{{ route('kasir.index') }}" class="btn btn-warning fw-bold">
            💰 Menu Kasir
        </a>
    </div>

    {{-- Statistik Utama --}}
    <div class="row g-4 mb-5">

        {{-- Total Produk --}}
        <div class="col-md-4">
            <div class="card glass-card text-center p-4 h-100">
                <div class="mb-3">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; color: #ffcc66;"></i>
                </div>
                <h5 class="fw-bold text-white">Total Produk</h5>
                <h3 class="fw-bold text-white">{{ $total_produk }}</h3>
                <p class="fw-bold text-white">Produk aktif di menu</p>
            </div>
        </div>

        {{-- Pesanan Pending --}}
        <div class="col-md-4">
            <a href="{{ route('admin.pesanan.pending') }}" class="text-decoration-none">
                <div class="card glass-card text-center p-4 h-100 hover-card">
                    <div class="mb-3">
                        <i class="bi bi-cart-check" style="font-size: 2.5rem; color: #ffb100;"></i>
                    </div>
                    <h5 class="fw-bold text-white">Pesanan Pending</h5>
                    <h3 class="fw-bold text-white">{{ $pesanan_pending }}</h3>
                    <p class="fw-bold text-white">Menunggu konfirmasi</p>
                </div>
            </a>
        </div>

        {{-- Total Penjualan --}}
        <div class="col-md-4">
            <a href="{{ route('admin.penjualan.detail') }}" class="text-decoration-none">
        <div class="card glass-card text-center p-4 h-100 hover-card">
            <div class="mb-3">
                <i class="bi bi-cash-stack" style="font-size: 2.5rem; color: #28e67a;"></i>
            </div>
            <h5 class="fw-bold text-white">Total Penjualan</h5>
            <h3 class="fw-bold text-white">{{ $total_pesanan }}</h3>
            <p class="fw-bold text-white">Penjualan bulan ini</p>
        </div>
        </a>
    </div>


    {{-- Chart Penjualan --}}
    <div class="row mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card glass-card p-3">
                <h5 class="text-white mb-3 fw-bold">Grafik Penjualan</h5>
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Menu Navigasi Cepat --}}
    <div class="row justify-content-center">
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.produk') }}" class="btn w-100 py-3 btn-coffee shadow-sm fw-bold">
                <i class="bi bi-box2-heart me-2"></i> Kelola Produk
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.pesanan') }}" class="btn w-100 py-3 btn-outline-light shadow-sm fw-bold">
                <i class="bi bi-receipt-cutoff me-2"></i> Lihat Pesanan
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.metode') }}" class="btn w-100 py-3 btn-outline-light shadow-sm fw-bold">
                <i class="bi bi-credit-card me-2"></i> Metode Pembayaran
            </a>
        </div>
    </div>

</div>

{{-- Chart.js --}}
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
            backgroundColor: '#ffcc66',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: '#ffffff' } }
        },
        scales: {
            x: {
                ticks: { color: '#ffffff' },
                grid: { display: false }
            },
            y: {
                ticks: { color: '#ffffff' },
                grid: { color: 'rgba(255,255,255,0.2)' }
            }
        }
    }
});
</script>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
