@extends('admin.layout')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-dark mb-4">➕ Tambah Produk Baru</h2>
<form action="{{ route('admin.produk.simpan') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" name="nama_produk" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control">
    </div>

    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" name="harga" class="form-control" required>
    </div>

    <div class="form-group mt-2">
    <label for="stok">Stok</label>
    <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', $product->stok ?? 0) }}" min="0" required>
</div>


    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar Produk</label>
        <input type="file" name="gambar" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Simpan Produk</button>
</form>

</div>

<style>
.btn-coffee {
    background-color: #c48a47;
    color: #fff;
    border: none;
}
.btn-coffee:hover {
    background-color: #a36d35;
}
</style>
@endsection
