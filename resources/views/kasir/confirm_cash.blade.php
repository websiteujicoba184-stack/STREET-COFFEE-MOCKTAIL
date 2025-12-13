@extends('admin.layout')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold text-center mb-4" style="color: black;">💰 Konfirmasi Pembayaran Tunai Online</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <div class="mb-4">
        <form method="GET" action="{{ route('kasir.confirm.cash.page') }}" class="d-flex">
            <input type="text" name="search" value="{{ $search }}" class="form-control me-2" placeholder="Cari berdasarkan Kode Pesanan..." style="color: black;">
            <button type="submit" class="btn btn-primary">🔍 Cari</button>
        </form>
    </div>

    @if($pendingOrders->isEmpty())
        <div class="alert alert-info text-center">
            Tidak ada pesanan tunai yang menunggu konfirmasi.
        </div>
    @else
        <div class="row">
            @foreach($pendingOrders as $order)
            <div class="col-md-6 mb-4">
                <div class="card bg-light shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pesanan #{{ $order->id }}</h5>
                        <p class="card-text" style="color: black;">
                            <strong>Nama:</strong> {{ $order->nama_pemesan }}<br>
                            <strong>Kode Pembayaran:</strong> {{ $order->kode_pembayaran }}<br>
                            <strong>Total:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </p>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($order->items as $item)
                                <li class="list-group-item" style="color: black;">{{ $item->product->nama_produk }} (x{{ $item->jumlah }}) - Rp {{ number_format($item->subtotal, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                        <form action="{{ route('kasir.confirm.cash') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <button type="submit" class="btn btn-success">✅ Konfirmasi</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <div class="text-center mt-3">
        <a href="{{ route('kasir.index') }}" class="btn btn-secondary">Kembali ke Kasir</a>
    </div>
</div>
@endsection
