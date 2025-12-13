@extends('customer.layout_customer')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold text-dark">🕓 Riwayat Pesanan</h2>

    @if ($orders->isEmpty())
        <div class="alert alert-info text-center">
            Belum ada riwayat pesanan.
        </div>
    @else
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="text-start">
                                @foreach ($order->items as $item)
                                    <div>- {{ $item->product->nama_produk }} (x{{ $item->jumlah }})</div>
                                @endforeach
                            </td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if ($order->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif ($order->status === 'batal')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<style>
    body {
    background: url('{{ asset("images/img1.jpg") }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    position: relative;
}
</style>
@endsection
