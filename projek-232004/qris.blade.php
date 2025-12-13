@extends('customer.layout_customer')

@section('content')
<div class="text-center py-5">
    <h3 class="fw-bold text-brown mb-3">QRIS Pembayaran</h3>
    <p>Scan QR di bawah ini untuk membayar pesanan kamu</p>

    <img src="{{ asset('images/qr.jpg') }}" alt="QRIS" class="img-fluid mb-3" width="250">
    <p><strong>Total:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
    <a href="{{ route('customer.orders') }}" class="btn btn-success">Sudah Bayar</a>
</div>
@endsection
