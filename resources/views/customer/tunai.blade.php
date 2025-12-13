@extends('customer.layout_customer')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 p-4 text-center">
        <h2 class="fw-bold text-brown mb-3">💵 Pembayaran Tunai</h2>

        <p class="fs-5">
            Terima kasih <strong>{{ $order->nama_pemesan }}</strong>! <br>
            Silakan lakukan pembayaran langsung di kasir kami.
        </p>

        <div class="alert alert-warning mt-4">
            <h4 class="fw-bold">Kode Pembayaran Kamu:</h4>
            <h2 class="text-danger fw-bold mt-2">{{ $order->kode_pembayaran }}</h2>
            <p class="mt-2">Tunjukkan kode ini ke kasir untuk melanjutkan pembayaran.</p>
        </div>

        <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary mt-4">
            Lihat Status Pesanan
        </a>
    </div>
</div>

<style>
.text-brown {
    color: #5c3d2e;
}
.card {
    background-color: #fffef9;
    border-radius: 16px;
}
</style>
@endsection
