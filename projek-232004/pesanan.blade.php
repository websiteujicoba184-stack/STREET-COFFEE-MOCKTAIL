@extends('admin.layout')

@section('content')
<div class="container mt-4">
  <h2>Daftar Pesanan Masuk</h2>

  @if($orders->isEmpty())
      <div class="alert alert-warning">Belum ada pesanan masuk.</div>
  @else
  <table class="table table-bordered">
      <thead class="table-dark">
          <tr>
              <th>No</th>
              <th>Nama Pemesan</th>
              <th>Metode</th>
              <th>Status</th>
              <th>Total</th>
              <th>Tanggal</th>
          </tr>
      </thead>
      <tbody>
          @foreach($orders as $i => $order)
          <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $order->nama_pemesan }}</td>
              <td>{{ $order->metode_pembayaran }}</td>
              <td>{{ $order->status }}</td>
              <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
              <td>{{ $order->created_at->format('d M Y H:i') }}</td>
          </tr>
          @endforeach
      </tbody>
  </table>
  @endif
</div>
@endsection
