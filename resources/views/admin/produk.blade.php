@extends('admin.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">☕ Daftar Produk</h2>
        <a href="{{ route('admin.produk.tambah') }}" class="btn btn-coffee">
            <i class="bi bi-plus-circle me-2"></i>Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle shadow-sm">
            <thead class="table-dark">
                <tr class="text-center">
                    <th width="5%">#</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stock</th>
                    <th>Deskripsi</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produk as $p)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->nama_produk }}</td>
                    <td>{{ $p->kategori }}</td>
                    <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td>{{ number_format($p->stok, 0, ',', '.') }}</td>
                    <td>{{ $p->deskripsi }}</td>
                    <td>
                        <a href="{{ route('admin.produk.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.produk.hapus', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.btn-coffee {
    background-color: #c48a47;
    color: #fff;
    border: none;
    transition: 0.3s;
}
.btn-coffee:hover {
    background-color: #a36d35;
    color: #fff;
}
.table {
    background-color: #fffdf8;
    border-radius: 10px;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
