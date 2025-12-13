@extends('admin.layout_admin')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold text-center mb-4 text-brown">💵 Konfirmasi Pembayaran Tunai</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <form action="{{ route('admin.konfirmasi.tunai') }}" method="POST" class="p-4 bg-light rounded shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="fw-semibold">Masukkan Kode Pembayaran dari Customer</label>
            <input type="text" name="kode_pembayaran" class="form-control" placeholder="Contoh: SCM-2025-6743" required>
        </div>

        <button type="submit" class="btn btn-success w-100">✅ Konfirmasi Pembayaran</button>
    </form>
</div>

<style>
.text-brown { color: #5c3d2e; }
.btn-success { background-color: #4CAF50; border: none; }
.btn-success:hover { background-color: #45a049; }
</style>
@endsection
