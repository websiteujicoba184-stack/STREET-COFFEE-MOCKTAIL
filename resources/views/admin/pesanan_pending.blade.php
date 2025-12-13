@extends('admin.layout')

@section('content')
<div class="container mt-4">
  <h2>Daftar Pesanan Pending</h2>

  @if($orders->isEmpty())
      <div class="alert alert-warning">Belum ada pesanan pending.</div>
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
              <th>Aksi</th>
          </tr>
      </thead>
      <tbody>
          @foreach($orders as $i => $order)
          <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $order->nama_pemesan }}</td>
              <td>{{ $order->metode_pembayaran }}</td>
              <td>
                  @if($order->status === 'pending')
                      <span class="badge bg-warning">Pending</span>
                  @elseif($order->status === 'on_progress')
                      <span class="badge bg-primary">Diproses</span>
                  @elseif($order->status === 'selesai')
                      <span class="badge bg-success">Selesai</span>
                  @else
                      <span class="badge bg-secondary">{{ $order->status }}</span>
                  @endif
              </td>
              <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
              <td>{{ $order->created_at->format('d M Y H:i') }}</td>
              <td>
                  @if($order->status === 'pending')
                      <form action="{{ route('admin.ubahStatus', $order->id) }}" method="POST" style="display: inline;">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="status" value="on_progress">
                          <button type="submit" class="btn btn-success btn-sm"
                                  onclick="return confirm('Konfirmasi pesanan ini untuk diproses?')">
                              <i class="bi bi-check-circle"></i> Konfirmasi
                          </button>
                      </form>
                  @else
                      <span class="text-muted">-</span>
                  @endif
              </td>
          </tr>
          @endforeach
      </tbody>
  </table>
  @endif
</div>
@endsection
