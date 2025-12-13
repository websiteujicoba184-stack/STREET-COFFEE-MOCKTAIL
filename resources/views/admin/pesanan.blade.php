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
                  @if($order->status === 'on_progress')
                      <form method="POST" action="{{ route('admin.ubahStatus', $order->id) }}" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="status" value="selesai">
                          <button type="submit" class="btn btn-success btn-sm"
                                  onclick="return confirm('Apakah pesanan ini sudah diantar?')">
                              <i class="bi bi-check-circle"></i> Sudah Diantar
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
