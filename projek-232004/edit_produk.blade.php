@extends('admin.layout')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm p-4">
        <h2 class="fw-bold text-dark mb-4 text-center">✏️ Edit Produk</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nama_produk" class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" class="form-control" 
                       value="{{ old('nama_produk', $produk->nama_produk) }}" required>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label fw-semibold">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" class="form-control"
                       value="{{ old('harga', $produk->harga) }}" required>
            </div>

            <div class="form-group mt-2">
            <label for="stok">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', $product->stok ?? 0) }}" min="0" required>
            </div>


            <div class="mb-3">
                <label for="kategori" class="form-label fw-semibold">Kategori</label>
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Kopi" {{ $produk->kategori == 'Kopi' ? 'selected' : '' }}>Kopi</option>
                    <option value="Mocktail" {{ $produk->kategori == 'Mocktail' ? 'selected' : '' }}>Mocktail</option>
                    <option value="Signature" {{ $produk->kategori == 'Signature' ? 'selected' : '' }}>Signature</option>
                    <option value="Non-Coffee" {{ $produk->kategori == 'Non-Coffee' ? 'selected' : '' }}>Non-Coffee</option>
                    <option value="Snack" {{ $produk->kategori == 'Snack' ? 'selected' : '' }}>Snack</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.produk') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-coffee">
                    <i class="bi bi-check-circle me-2"></i> Perbarui Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Style tema kopi -->
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
.card {
    background-color: #fffdf8;
</style>

<!-- Bootstrap icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
