@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-brown-800 font-bold">💵 Kasir Street Coffee Mocktail</h2>

    <form id="kasirForm" method="POST" action="{{ route('kasir.checkout') }}">
        @csrf
        <table class="table table-bordered">
            <thead class="bg-brown-200">
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="produkList">
                @foreach($produks as $produk)
                <tr>
                    <td>{{ $produk->nama_produk }}</td>
                    <td>{{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td>
                        <input type="number" name="items[{{ $produk->id }}][qty]" class="form-control qty" data-harga="{{ $produk->harga }}" min="0" value="0">
                        <input type="hidden" name="items[{{ $produk->id }}][id]" value="{{ $produk->id }}">
                    </td>
                    <td class="subtotal">Rp 0</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-3">
            <h4>Total: <span id="totalHarga">Rp 0</span></h4>
            <div class="mt-2">
                <label>Uang Dibayar:</label>
                <input type="number" name="uang_dibayar" id="uangDibayar" class="form-control w-25 d-inline-block">
            </div>
            <div class="mt-2">
                <h5>Kembalian: <span id="kembalian">Rp 0</span></h5>
            </div>
            <button type="submit" class="btn btn-success mt-3">Selesaikan Transaksi</button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('.qty').forEach(input => {
    input.addEventListener('input', hitungTotal);
});

document.getElementById('uangDibayar').addEventListener('input', hitungKembalian);

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.qty').forEach(input => {
        const harga = parseInt(input.dataset.harga);
        const qty = parseInt(input.value) || 0;
        const subtotal = harga * qty;
        input.closest('tr').querySelector('.subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        total += subtotal;
    });
    document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function hitungKembalian() {
    const total = parseInt(document.getElementById('totalHarga').textContent.replace(/[^\d]/g, '')) || 0;
    const uang = parseInt(document.getElementById('uangDibayar').value) || 0;
    const kembali = uang - total;
    document.getElementById('kembalian').textContent = 'Rp ' + kembali.toLocaleString('id-ID');
}
</script>
@endsection
