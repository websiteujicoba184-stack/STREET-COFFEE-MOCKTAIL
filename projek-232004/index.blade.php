@extends('admin.layout')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-3 text-center">💵 Kasir Street Coffee Mocktail</h2>

    <form action="{{ route('kasir.checkout') }}" method="POST">
        @csrf
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                @foreach($produks as $p)
                <tr>
                    <td>{{ $p->nama_produk }}</td>
                    <td>{{ number_format($p->harga) }}</td>
                    <td>{{ $p->stok }}</td>
                    <td>
                        <input type="number" name="items[{{ $loop->index }}][qty]" class="form-control text-center" min="0" max="{{ $p->stok }}" value="0"
                            onchange="updateSubtotal({{ $loop->index }}, {{ $p->harga }})">
                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $p->id }}">
                    </td>
                    <td id="subtotal-{{ $loop->index }}">0</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end my-3">
            <h5>Total: Rp <span id="total">0</span></h5>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="uang_dibayar" class="form-label">Uang Dibayar</label>
                <input type="number" name="uang_dibayar" id="uang_dibayar" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="kembalian" class="form-label">Kembalian</label>
                <input type="text" id="kembalian" class="form-control" readonly>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Selesaikan Transaksi</button>
    </form>
</div>

<script>
function updateSubtotal(index, harga) {
    const qty = document.querySelector(`[name="items[${index}][qty]"]`).value;
    const subtotal = qty * harga;
    document.getElementById(`subtotal-${index}`).innerText = subtotal.toLocaleString();
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        total += parseInt(el.innerText.replace(/\D/g, '')) || 0;
    });
    document.getElementById('total').innerText = total.toLocaleString();

    const uang = document.getElementById('uang_dibayar').value || 0;
    document.getElementById('kembalian').value = (uang - total).toLocaleString();
}

document.getElementById('uang_dibayar').addEventListener('input', updateTotal);
</script>
@endsection
