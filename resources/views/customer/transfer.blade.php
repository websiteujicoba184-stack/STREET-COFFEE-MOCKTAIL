@extends('customer.layout_customer')

@section('content')
<div class="text-center py-5">
    <h3 class="fw-bold text-brown mb-3">Transfer Bank</h3>
    <p>Silakan lakukan transfer ke salah satu rekening berikut:</p>

    <div class="row justify-content-center mb-4">
        @foreach($rekenings as $rekening)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $rekening['bank'] }}</h5>
                    <p class="mb-1"><strong>No. Rekening:</strong></p>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control text-center fw-bold"
                               value="{{ $rekening['no_rekening'] }}" readonly
                               id="rekening-{{ $loop->index }}">
                        <button class="btn btn-outline-primary" type="button"
                                onclick="copyToClipboard('rekening-{{ $loop->index }}')">
                            <i class="bi bi-clipboard me-1"></i>Salin
                        </button>
                    </div>
                    <p><strong>Nama:</strong> {{ $rekening['nama'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <p class="mb-0"><strong>Total Pembayaran:</strong></p>
            <h4 class="text-success fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h4>
        </div>
    </div>

    @if($order->status === 'pending')
        <form method="POST" action="{{ route('customer.confirmPayment', $order->id) }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle me-2"></i>Saya Sudah Transfer
            </button>
        </form>
    @else
        <div class="alert alert-success mt-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            Transfer telah dikonfirmasi! Pesanan sedang diproses.
        </div>
    @endif
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(element.value).then(function() {
        // Show success feedback
        const button = element.nextElementSibling;
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check2"></i>';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');

        setTimeout(function() {
            button.innerHTML = originalIcon;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        // Fallback for older browsers
        document.execCommand('copy');
    });
}
</script>
@endsection
