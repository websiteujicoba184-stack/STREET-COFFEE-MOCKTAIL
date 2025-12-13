@extends('admin.layout')

@section('content')
<div class="py-4">

    {{-- Judul Halaman --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold text-white" style="text-shadow:0 3px 10px rgba(0,0,0,0.8);">
            📊 Laporan Transaksi
        </h1>
        <p class="text-light">
            Street Coffee Mocktail - Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}
        </p>
        <hr class="w-50 mx-auto" style="border-color: #c48a47;">
    </div>

    {{-- Filter Section --}}
    <div class="mb-4">
        <div class="card glass-card p-4">
            <h5 class="text-white mb-3 fw-bold">🔍 Filter Laporan</h5>
            <form method="GET" action="{{ route('admin.report') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label text-white fw-bold">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}"
                           class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label text-white fw-bold">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}"
                           class="form-control bg-dark text-white border-secondary">
                </div>
                <div class="col-md-3">
                    <label for="metode_pembayaran" class="form-label text-white fw-bold">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran"
                            class="form-select bg-dark text-white border-secondary">
                        <option value="">Semua Metode</option>
                        <option value="Tunai" {{ $payment == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="QRIS" {{ $payment == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="Transfer" {{ $payment == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="kasir" class="form-label text-white fw-bold">Kasir</label>
                    <select name="kasir" id="kasir" class="form-select bg-dark text-white border-secondary">
                        <option value="">Semua Kasir</option>
                        @foreach($kasirs as $kasir_name)
                          <option value="{{ $kasir_name }}" {{ $cashier == $kasir_name ? 'selected' : '' }}>{{ $kasir_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="bi bi-search me-2"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('admin.report') }}" class="btn btn-outline-light fw-bold">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                        </a>
                        <button type="button" id="print-btn" class="btn btn-info fw-bold">
                            <i class="bi bi-printer me-2"></i>Cetak Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card glass-card text-center p-4 h-100">
                <div class="mb-3">
                    <i class="bi bi-receipt" style="font-size: 2.5rem; color: #ffcc66;"></i>
                </div>
                <h5 class="fw-bold text-white">Total Transaksi</h5>
                <h3 class="fw-bold text-white">{{ number_format($totalTransactions) }}</h3>
                <p class="text-light">Transaksi dalam periode</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card text-center p-4 h-100">
                <div class="mb-3">
                    <i class="bi bi-cash-stack" style="font-size: 2.5rem; color: #28e67a;"></i>
                </div>
                <h5 class="fw-bold text-white">Total Pendapatan</h5>
                <h3 class="fw-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-light">Total pemasukan</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card text-center p-4 h-100">
                <div class="mb-3">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; color: #ffb100;"></i>
                </div>
                <h5 class="fw-bold text-white">Item Terjual</h5>
                <h3 class="fw-bold text-white">{{ number_format($totalItems) }}</h3>
                <p class="text-light">Total produk terjual</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card text-center p-4 h-100">
                <div class="mb-3">
                    <i class="bi bi-graph-up" style="font-size: 2.5rem; color: #dc3545;"></i>
                </div>
                <h5 class="fw-bold text-white">Rata-rata Transaksi</h5>
                <h3 class="fw-bold text-white">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</h3>
                <p class="text-light">Rata-rata per transaksi</p>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row g-4 mb-4">
        {{-- Bar Chart --}}
        <div class="col-md-8">
            <div class="card glass-card p-4">
                <h5 class="text-white mb-3 fw-bold">
                    <i class="bi bi-bar-chart-line me-2"></i>Grafik Penjualan Bulanan
                </h5>
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="col-md-4">
            <div class="card glass-card p-4">
                <h5 class="text-white mb-3 fw-bold">
                    <i class="bi bi-pie-chart me-2"></i>Penjualan per Produk
                </h5>
                <canvas id="productChart" height="300"></canvas>
            </div>
        </div>
    </div>

    {{-- Transaction Details Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card glass-card p-4">
                <h5 class="text-white mb-3 fw-bold">
                    <i class="bi bi-table me-2"></i>Detail Transaksi
                </h5>
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th class="text-white">Tanggal & Waktu</th>
                                <th class="text-white">Nama Pemesan</th>
                                <th class="text-white">Nama Pesanan</th>
                                <th class="text-white">Jumlah</th>
                                <th class="text-white">Total Harga</th>
                                <th class="text-white">Metode Pembayaran</th>
                                <th class="text-white">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="text-white">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="text-white fw-bold">
                                            {{ $order->nama_pemesan }}
                                        </td>
                                        <td class="text-white">
                                            {{ $item->product->nama_produk }}
                                        </td>
                                        <td class="text-white">
                                            {{ $item->jumlah }}
                                        </td>
                                        <td class="text-white fw-bold">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($order->metode_pembayaran == 'Tunai') bg-success
                                                @elseif($order->metode_pembayaran == 'QRIS') bg-info
                                                @elseif($order->metode_pembayaran == 'Transfer') bg-warning
                                                @else bg-secondary
                                                @endif">
                                                {{ $order->metode_pembayaran }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($order->status == 'selesai') bg-success
                                                @elseif($order->status == 'on_progress') bg-warning
                                                @elseif($order->status == 'pending') bg-danger
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-light py-4">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Tidak ada data transaksi untuk filter yang dipilih
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Additional Statistics --}}
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card glass-card p-4">
                <h5 class="text-white mb-3 fw-bold">
                    <i class="bi bi-trophy me-2"></i>Produk Terlaris
                </h5>
                @if($bestSeller)
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white mb-1">{{ $bestSeller->nama_produk }}</h6>
                            <p class="text-light mb-0">{{ $bestSeller->qty }} item terjual</p>
                        </div>
                        <i class="bi bi-star-fill text-warning fs-4"></i>
                    </div>
                @else
                    <p class="text-light mb-0">Belum ada data penjualan</p>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card glass-card p-4">
                <h5 class="text-white mb-3 fw-bold">
                    <i class="bi bi-person-check me-2"></i>Top Customer
                </h5>
                @forelse($topBuyers->take(3) as $buyer)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="text-white fw-bold">{{ $buyer->name }}</span>
                            <br>
                            <small class="text-light">{{ $buyer->total_orders }} transaksi</small>
                        </div>
                        <span class="text-success fw-bold">Rp {{ number_format($buyer->total_spent, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-light mb-0">Belum ada data customer</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Sales Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($totalsByMonth),
                    backgroundColor: '#ffcc66',
                    borderColor: '#c48a47',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { color: '#ffffff' }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#ffffff' },
                        grid: { display: false }
                    },
                    y: {
                        ticks: {
                            color: '#ffffff',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: { color: 'rgba(255,255,255,0.2)' }
                    }
                }
            }
        });
    }

    // Product Sales Pie Chart
    const productCtx = document.getElementById('productChart');
    if (productCtx) {
        const productData = @json($penjualanProduk);
        new Chart(productCtx, {
            type: 'doughnut',
            data: {
                labels: productData.map(item => item.nama_produk),
                datasets: [{
                    data: productData.map(item => item.total),
                    backgroundColor: [
                        '#ffcc66',
                        '#28e67a',
                        '#ffb100',
                        '#dc3545',
                        '#6f42c1',
                        '#20c997'
                    ],
                    borderWidth: 2,
                    borderColor: 'rgba(255,255,255,0.3)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { color: '#ffffff' },
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});

// Print functionality
document.getElementById('print-btn').addEventListener('click', function() {
    window.print();
});
</script>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
@media print {
  .no-print {
    display: none !important;
  }

  body {
    background: white !important;
  }

  .glass-card {
    background: white !important;
    border: 1px solid #ddd !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
  }

  .text-white {
    color: black !important;
  }

  .text-light {
    color: #666 !important;
  }
}
</style>
@endsection
