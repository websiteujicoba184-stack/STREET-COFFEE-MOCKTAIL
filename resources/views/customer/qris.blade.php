@extends('customer.layout_customer')

@section('content')
<div class="text-center py-5">
    <h3 class="fw-bold text-brown mb-3">QRIS Pembayaran</h3>
    <p>Scan QR di bawah ini untuk membayar pesanan kamu</p>

    <img src="{{ asset('images/qr.jpg') }}" alt="QRIS" class="img-fluid mb-3" width="250">
    <p><strong>Total:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>

    @if($order->status === 'pending')
        <form method="POST" action="{{ route('customer.confirmPayment', $order->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle me-2"></i>Saya Sudah Bayar
            </button>
        </form>
    @else
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>
            Pembayaran telah dikonfirmasi! Pesanan sedang diproses.
        </div>
    @endif
</div>
@endsection
