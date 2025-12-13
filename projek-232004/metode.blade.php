@extends('admin.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">💰 Metode Pembayaran</h2>
        <a href="#" class="btn btn-coffee">
            <i class="bi bi-plus-circle me-2"></i>Tambah Metode
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
                    <th>Nama Metode</th>
                    <th>Nomor / Rekening</th>
                    <th>Pemilik</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Contoh data dummy --}}
                <tr class="text-center">
                    <td>1</td>
                    <td>Gopay</td>
                    <td>0812-3456-7890</td>
                    <td>Street Coffee</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
                <tr class="text-center">
                    <td>2</td>
                    <td>BCA</td>
                    <td>1234567890</td>
                    <td>Street Coffee</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
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
    overflow: hidden;
}
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection
