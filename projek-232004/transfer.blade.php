@extends('customer.layout_customer')

@section('content')
<div class="text-center py-5">
    <h3 class="fw-bold text-brown mb-3">Transfer Bank</h3>
    <p>Silakan lakukan transfer ke rekening berikut:</p>

    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <p><strong>Bank:</strong> {{ $rekening['bank'] }}</p>
            <p><strong>No. Rekening:</strong> {{ $rekening['no_rekening'] }}</p>
            <p><strong>Nama:</strong> {{ $rekening['nama'] }}</p>
            <hr>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
        </div>
    </div>

    <a href="{{ route('customer.orders') }}" class="btn btn-success mt-4">Sudah Transfer</a>
</div>
@endsection
