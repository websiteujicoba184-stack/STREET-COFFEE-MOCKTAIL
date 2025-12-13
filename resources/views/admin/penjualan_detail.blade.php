@extends('admin.layout')

@section('content')

<div class="py-4">

    <h1 class="fw-bold text-white mb-4">Laporan Penjualan</h1>

    <!-- Profit Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card glass-card p-4">
                <h4 class="text-white fw-bold">Total Omset</h4>
                <h2 class="fw-bold text-white">
                    Rp {{ number_format($totalOmset, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card glass-card p-4">
                <h4 class="text-white fw-bold">Profit (Untung/Rugi)</h4>
                <h2 class="fw-bold text-white">
                    Rp {{ number_format($profit, 0, ',', '.') }}
                </h2>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan Bulanan -->
    <div class="card glass-card p-4 mb-4">
        <h4 class="text-white fw-bold">Grafik Penjualan Bulanan</h4>
        <canvas id="chartBulanan" height="120"></canvas>
    </div>

    <!-- Pie Chart Produk -->
    <div class="card glass-card p-4 mb-4">
        <h4 class="text-white fw-bold">Penjualan per Produk</h4>
        <canvas id="chartPie" height="60"></canvas>
    </div>

    <!-- Best Seller -->
    <div class="card glass-card p-4">
        <h4 class="text-white fw-bold">Produk Terlaris</h4>

        @if($bestSeller)
        <p class="text-white fs-4 fw-bold">
            {{ $bestSeller->nama_produk }} — {{ $bestSeller->qty }} terjual

        </p>
        @else
        <p class="text-white">Belum ada transaksi.</p>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === CHART PENJUALAN BULANAN ===
    const bulan = {!! json_encode($penjualanBulanan->pluck('bulan')) !!};
    const total = {!! json_encode($penjualanBulanan->pluck('total')) !!};

    new Chart(document.getElementById('chartBulanan'), {
        type: 'bar',
        data: {
            labels: bulan.map(b => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][b-1]),
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: total,
                backgroundColor: '#ffcc66',
                borderRadius: 8
            }]
        }
    });

    // === PIE CHART PRODUK ===
    const produkNama = {!! json_encode($penjualanProduk->pluck('nama_produk')) !!};
    const produkTotal = {!! json_encode($penjualanProduk->pluck('total')) !!};

    new Chart(document.getElementById('chartPie'), {
        type: 'pie',
        data: {
            labels: produkNama,
            datasets: [{
                data: produkTotal,
                backgroundColor: ['#ffcc66','#ff884d','#66ff99','#66ccff','#ff6666','#bb99ff']
            }]
        }
    });
</script>

@endsection
