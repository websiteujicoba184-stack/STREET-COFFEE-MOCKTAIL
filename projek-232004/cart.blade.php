@extends('customer.layout_customer')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown">🛒 Keranjang Belanja Kamu</h2>
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
            ← Kembali Belanja
        </a>
    </div>

    @if ($cart->isEmpty())
        <div class="alert alert-warning text-center">
            Keranjang kamu masih kosong 😢 <br>
            Yuk pilih menu favoritmu di 
            <a href="{{ route('customer.dashboard') }}">halaman utama</a>!
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle shadow-sm">
                <thead class="table-dark text-center">
                    <tr>
                        <th width="5%">#</th>
                        <th>Produk</th>
                        <th width="20%">Jumlah</th>
                        <th width="20%">Subtotal</th>
                        <th width="30%">Catatan (Opsional)</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    @foreach ($cart as $index => $item)
                    <tr data-id="{{ $item->id }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product->nama_produk }}</strong><br>
                            <small class="text-muted">Rp {{ number_format($item->product->harga, 0, ',', '.') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-sm btn-outline-danger btn-minus" data-id="{{ $item->id }}">−</button>
                                <span class="mx-2 jumlah-text">{{ $item->jumlah }}</span>
                                <button class="btn btn-sm btn-outline-success btn-plus" data-id="{{ $item->id }}">+</button>
                            </div>
                        </td>
                        <td class="text-center fw-semibold subtotal">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                        <td>
                            <input type="text" 
                                   name="notes[{{ $item->id }}]" 
                                   class="form-control form-control-sm" 
                                   placeholder="Contoh: Kurangin gula, tambah es"
                                   value="{{ $item->notes ?? '' }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TOTAL --}}
        <div class="mt-4 text-end">
            <h4 class="fw-bold">
                Total Bayar: <span id="totalBayar" class="text-success">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </h4>
        </div>

        {{-- FORM CHECKOUT --}}
        <form action="{{ route('checkout') }}" method="POST" class="mt-4 p-4 bg-light rounded shadow-sm">
            @csrf
            <div class="mb-3">
                <label class="fw-semibold">👤 Nama Pemesan</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">💳 Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Tunai">Tunai (Bayar di Kasir)</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Transfer">Transfer Bank</option>
                </select>
            </div>

            {{-- Dinamis sesuai metode pembayaran --}}
            <div id="qris-section" class="p-3 border rounded bg-white mb-3" style="display:none;">
                <p class="fw-semibold">Scan QR berikut untuk melakukan pembayaran:</p>
                <center><img src="{{ asset('images/qr.jpg') }}" alt="QRIS" width="200"></center>
                <center><p class="mt-2 small text-muted">Setelah pembayaran, sistem akan otomatis memverifikasi.</p></center>
            </div>

            <div id="transfer-section" class="p-3 border rounded bg-white mb-3" style="display:none;">
                <p class="fw-semibold mb-1">Transfer ke rekening berikut:</p>
                <div class="bg-light p-2 rounded">
                    <strong>Bank BCA</strong> <br>
                    No. Rekening: <strong>1234567890</strong><br>
                    a.n <strong>Street Coffee Mocktail</strong>
                </div>
                <div class="bg-light p-2 rounded">
                    <strong>Bank MANDIRI</strong> <br>
                    No. Rekening: <strong>987654321</strong><br>
                    a.n <strong>Street Coffee Mocktail</strong>
                </div>
                <div class="bg-light p-2 rounded">
                    <strong>Bank BRI</strong> <br>
                    No. Rekening: <strong>1357908642</strong><br>
                    a.n <strong>Street Coffee Mocktail</strong>
                </div>
                <p class="small text-muted mt-2">Kirim bukti pembayaran ke kasir untuk verifikasi.</p>
            </div>

            <div id="tunai-section" class="p-3 border rounded bg-white mb-3" style="display:none;">
                <p class="fw-semibold mb-1">Kode Pembayaran Tunai Kamu:</p>
                <h4 class="text-primary fw-bold" id="kode-bayar">
                    SCM-{{ date('Y') }}-{{ rand(1000,9999) }}
                </h4>
                <p class="small text-muted">Tunjukkan kode ini ke kasir saat pembayaran.</p>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                ✅ Lanjutkan Checkout
            </button>
        </form>
    @endif
</div>

{{-- JS Dinamis Metode Pembayaran --}}
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

// 🔹 Update jumlah produk (AJAX)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const isPlus = btn.classList.contains('btn-plus');
            const res = await fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: isPlus ? 'plus' : 'minus' })
            });
            const data = await res.json();
            if (data.success) {
                if (data.removed) {
                    document.querySelector(`tr[data-id="${id}"]`).remove();
                } else {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    row.querySelector('.jumlah-text').textContent = data.jumlah;
                    row.querySelector('.subtotal').textContent = "Rp " + new Intl.NumberFormat('id-ID').format(data.subtotal);
                }
                document.getElementById('totalBayar').textContent = "Rp " + new Intl.NumberFormat('id-ID').format(data.total);
            }
        });
    });
});
</script>

<style>
.text-brown { color: #ffffffff; }
.table { background-color: #fffef9; border-radius: 12px; }
.table th { background-color: #474741ff !important; color: #fff; }
.btn-outline-danger, .btn-outline-success { width: 32px; height: 32px; font-weight: bold; }
.btn-success { background-color: #474741ff; border:00a313ff ; }
.btn-success:hover { background-color: #f4f4f4ff; }


    body {
    background: url('{{ asset("images/img1.jpg") }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    position: relative;
}
</style>
@endsection
