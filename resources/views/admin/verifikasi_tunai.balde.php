@extends('admin.layout_admin')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center text-brown">💰 Verifikasi Pembayaran Tunai</h2>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @elseif (session('warning'))
        <div class="alert alert-warning text-center">{{ session('warning') }}</div>
    @endif

    {{-- Form Input Kode --}}
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <form action="{{ route('kasir.verifikasi.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kode_pembayaran" class="form-label fw-semibold">
                        Masukkan Kode Pembayaran dari Customer
                    </label>
                    <input type="text" name="kode_pembayaran" id="kode_pembayaran" class="form-control form-control-lg" placeholder="Contoh: SCM-2025-1234" required>
                </div>
                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                    ✅ Verifikasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.text-brown { color: #5c3d2e; }
</style>
@endsection
