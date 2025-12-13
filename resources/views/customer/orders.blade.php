@extends('customer.layout_customer')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-brown mb-4">📦 Pesanan Saya</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-warning text-center">
            Belum ada pesanan. Yuk buat pesanan pertama kamu ☕
        </div>
    @else
        @foreach($orders as $order)
        <div class="card mb-4 shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="fw-bold text-brown">Pesanan #{{ $order->id }}</h5>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning 
                        @elseif($order->status == 'on_progress') bg-info
                        @elseif($order->status == 'selesai') bg-success 
                        @else bg-danger @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <p class="mt-2 mb-1"><strong>Nama:</strong> {{ $order->nama_pemesan }}</p>
                <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $order->metode_pembayaran }}</p>

                {{-- Tampilkan kode pembayaran kalau tunai --}}
                @if($order->metode_pembayaran === 'Tunai' && $order->kode_pembayaran)
                    <p class="mb-1"><strong>Kode Pembayaran:</strong> 
                        <span class="text-primary fw-bold">{{ $order->kode_pembayaran }}</span>
                    </p>
                    <p class="small text-muted">Tunjukkan kode ini ke kasir untuk pembayaran.</p>
                @endif

                {{-- Detail Produk --}}
                <ul class="mt-3 list-group list-group-flush">
                    @foreach($order->items as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <strong>{{ $item->product->nama_produk }}</strong> (x{{ $item->jumlah }})
                                @if($item->notes)
                                    <br><small class="text-muted">Catatan: {{ $item->notes }}</small>
                                @endif
                            </div>
                            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="text-end mt-3">
                    <strong>Total: Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

<style>
.text-brown { color: #fcfbfaff; }
.card { background-color: #fffef9; }
body {
    background: url('{{ asset("images/img1.jpg") }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    position: relative;
}
</style>
</style>
@endsection
