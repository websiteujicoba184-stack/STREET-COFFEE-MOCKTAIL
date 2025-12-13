@extends('customer.layout_customer')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-light">🛒 Keranjang Belanja Kamu</h2>
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light rounded-pill px-4">
            ← Kembali Belanja
        </a>
    </div>

    @if ($cart->isEmpty())
        <div class="glass-card text-center text-light p-4 rounded-4">
            <h5>Keranjang kamu masih kosong 😢</h5>
            <p class="mt-2">
                Yuk pilih menu favoritmu di 
                <a href="{{ route('customer.dashboard') }}" class="text-warning fw-bold text-decoration-none">
                    halaman utama
                </a>!
            </p>
        </div>
    @else
        {{-- 🔹 Daftar Produk di Keranjang --}}
        <div class="glass-card p-3 rounded-4 shadow-lg">
            <table class="table mb-0 align-middle text-light">
                <thead class="text-warning text-center">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    @foreach ($cart as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong class="text-warning">{{ $item->product->nama_produk }}</strong><br>
                            <small>Rp {{ number_format($item->product->harga, 0, ',', '.') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-sm btn-outline-danger btn-minus" data-id="{{ $item->id }}">−</button>
                                <span class="mx-2 jumlah-text fw-bold">{{ $item->jumlah }}</span>
                                <button class="btn btn-sm btn-outline-success btn-plus" data-id="{{ $item->id }}">+</button>
                            </div>
                        </td>
                        <td class="text-center fw-semibold subtotal">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                        <td>
                            <input type="text" 
                                name="notes[{{ $item->id }}]" 
                                class="form-control form-control-sm bg-transparent text-light border-light rounded-pill" 
                                placeholder="Contoh: Kurangin gula, tambah es"
                                value="{{ $item->notes ?? '' }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 🔹 Total --}}
        <div class="glass-card mt-4 p-4 text-light rounded-4 text-end">
            <h4 class="fw-bold">
                Total Bayar: 
                <span id="totalBayar" class="text-warning">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </h4>
        </div>

        {{-- 🔹 Form Checkout --}}
        <form action="{{ route('checkout') }}" method="POST" class="glass-card mt-4 p-4 rounded-4 text-light">
            @csrf
            <div class="mb-3">
                <label class="fw-semibold">👤 Nama Pemesan</label>
                <input type="text" name="nama" class="form-control bg-transparent text-light border-light rounded-pill" placeholder="Masukkan nama Anda">
            </div>

            <div class="mb-3">
                <label class="fw-semibold">💳 Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select bg-transparent text-light border-light rounded-pill">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Tunai">Tunai (Bayar di Kasir)</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>
            </div>

            {{-- 🔸 Section Dinamis --}}
            <div id="qris-section" class="glass-inner p-3 rounded-3 mb-3" style="display:none;">
                <p class="fw-semibold">Scan QR berikut untuk melakukan pembayaran:</p>
                <center><img src="{{ asset('images/qr.jpg') }}" alt="QRIS" width="200"></center>
                <center><p class="mt-2 small text-warning">Setelah pembayaran, sistem akan otomatis memverifikasi.</p></center>
            </div>

            <div id="transfer-section" class="glass-inner p-3 rounded-3 mb-3" style="display:none;">
                <p class="fw-semibold mb-1">Transfer ke rekening berikut:</p>
                <ul class="list-unstyled mb-0">
                    <li>🏦 <strong>BCA</strong> - 1234567890 a.n <strong>Street Coffee Mocktail</strong></li>
                    <li>🏦 <strong>Mandiri</strong> - 987654321 a.n <strong>Street Coffee Mocktail</strong></li>
                    <li>🏦 <strong>BRI</strong> - 1357908642 a.n <strong>Street Coffee Mocktail</strong></li>
                </ul>
            </div>

            <div id="tunai-section" class="glass-inner p-3 rounded-3 mb-3" style="display:none;">
                <p class="fw-semibold mb-1">Kode Pembayaran Tunai Kamu:</p>
                <h4 class="text-warning fw-bold" id="kode-bayar">
                    SCM-{{ date('Y') }}-{{ rand(1000,9999) }}
                </h4>
                <p class="small text-light">Tunjukkan kode ini ke kasir saat pembayaran.</p>
            </div>

            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold rounded-pill text-dark">
                ✅ Lanjutkan Checkout
            </button>
        </form>
    @endif
</div>

{{-- 🔹 SCRIPT --}}
<script>
document.getElementById('metode_pembayaran').addEventListener('change', function() {
    const qris = document.getElementById('qris-section');
    const transfer = document.getElementById('transfer-section');
    const tunai = document.getElementById('tunai-section');
    qris.style.display = transfer.style.display = tunai.style.display = 'none';
    if (this.value === 'QRIS') qris.style.display = 'block';
    if (this.value === 'Transfer') transfer.style.display = 'block';
    if (this.value === 'Tunai') tunai.style.display = 'block';
});

// 🔁 Update Jumlah Produk (Tambah / Kurang)
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = '{{ csrf_token() }}';
    document.querySelectorAll('.btn-plus, .btn-minus').forEach(button => {
        button.addEventListener('click', async () => {
            const id = button.dataset.id;
            const isPlus = button.classList.contains('btn-plus');
            const action = isPlus ? 'plus' : 'minus';

            try {
                const response = await fetch(`{{ url('/cart/update') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ action })
                });

                const data = await response.json();

                if (data.success) {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (data.removed) {
                        row.remove();
                    } else {
                        row.querySelector('.jumlah-text').textContent = data.jumlah;
                        row.querySelector('.subtotal').textContent = "Rp " + new Intl.NumberFormat('id-ID').format(data.subtotal);
                    }
                    document.getElementById('totalBayar').textContent = "Rp " + new Intl.NumberFormat('id-ID').format(data.total);
                }
            } catch (error) {
                console.error('Gagal update keranjang:', error);
            }
        });
    });
});
</script>

{{-- 🔹 STYLE --}}
<style>
body {
    background: url('{{ asset("images/img1.jpg") }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
}

/* 🌫️ Glass Effect */
.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
}
.glass-inner {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* 🧊 Table transparan */
.table td, .table th {
    background: rgba(0, 0, 0, 0.25) !important;
    border: none;
    color: #fff;
}
.table th {
    background: rgba(0, 0, 0, 0.45) !important;
    font-weight: 600;
    text-transform: uppercase;
}

/* 🎨 Input transparan */
.form-control, .form-select {
    background: rgba(255, 255, 255, 0.15) !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.3);
}
.form-select option {
    background-color: #2b2b2b;
    color: #fff;
}
.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

/* ✨ Tombol hover */
.btn-outline-light:hover {
    background: #ffcc00;
    color: #000;
}
.btn-warning {
    background: #ffcc00;
    color: #000;
    border: none;
}
.btn-warning:hover {
    background: #ffe680;
    color: #000;
}
.text-warning {
    color: #ffcc00 !important;
}
</style>
@endsection
