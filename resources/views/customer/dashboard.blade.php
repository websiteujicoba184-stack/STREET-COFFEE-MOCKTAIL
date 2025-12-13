@extends('customer.layout_customer')

@section('content')

<style>
/* 🌅 Background full page + overlay gelap biar teks jelas */
body {
    background: url('{{ asset("images/img1.jpg") }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    position: relative;
}

body::before {
    content: "";
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.55); /* lapisan gelap transparan */
    z-index: -1;
}

/* 🌫️ Efek glass / blur pada kartu */
.glass-card {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.3);
}

/* 🌟 Animasi fade-in */
.product-item {
    opacity: 0;
    transform: translateY(25px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.product-item.show {
    opacity: 1;
    transform: translateY(0);
}

/* ✨ Kartu produk */
.card-product {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-product:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
}

/* 🛒 Tombol keranjang */
.btn-cart {
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-cart:hover {
    background-color: #ffcc00;
    color: #000;
    font-weight: 600;
}

/* 🌈 Judul utama */
.title-text {
    font-weight: 800;
    color: #fff;
    text-shadow: 0 3px 10px rgba(0,0,0,0.7);
    letter-spacing: 1px;
}

/* ✨ Subjudul */
.subtitle {
    color: #f1f1f1;
    font-size: 1.05rem;
    text-shadow: 0 2px 6px rgba(0,0,0,0.6);
}

/* 🩷 Tab kategori */
.nav-pills .nav-link {
    border-radius: 25px;
    margin: 3px;
    color: #fff;
    background-color: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    transition: 0.3s;
}
.nav-pills .nav-link:hover {
    background-color: rgba(255,255,255,0.25);
}
.nav-pills .nav-link.active {
    background-color: #ffcc00;
    color: #000;
    font-weight: 600;
}

/* 🔤 Teks di kartu produk */
.card-product h5,
.card-product p,
.card-product h6 {
    text-shadow: 0 2px 6px rgba(0,0,0,0.8);
}
</style>

<div class="container mt-5">
    <center>
        <h2 class="title-text mb-2">☕Menu Street Coffee Mocktail </h2>
        <p class="subtitle">Temukan minuman favoritmu!</p>
    </center>

    <!-- 🔹 Tab Kategori -->
    <ul class="nav nav-pills justify-content-center mb-4" id="kategoriTab">
        <li class="nav-item">
            <button class="nav-link active" data-category="all">Semua</button>
        </li>
        @foreach($kategoriList as $kategori)
            <li class="nav-item">
                <button class="nav-link" data-category="{{ $kategori }}">{{ $kategori }}</button>
            </li>
        @endforeach
    </ul>

    <!-- 🔹 Daftar Produk -->
    <div class="row" id="produkList">
        @foreach($products as $p)
        <div class="col-md-3 mb-4 product-item" data-category="{{ $p->kategori }}">
            <div class="card glass-card card-product text-center h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-white">{{ $p->nama_produk }}</h5>
                        <p class="text-light mb-1">{{ $p->kategori }}</p>
                        <h6 class="fw-bold text-warning">Rp {{ number_format($p->harga, 0, ',', '.') }}</h6>
                    </div>
                    <button 
                        type="button" 
                        class="btn btn-outline-light btn-cart mt-3"
                        data-url="{{ route('cart.add', $p->id) }}">
                        🛒 Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- 🔹 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll("#kategoriTab .nav-link");
    const products = document.querySelectorAll(".product-item");

    function showProducts(filteredProducts) {
        products.forEach(p => p.classList.remove("show"));
        setTimeout(() => {
            products.forEach(p => {
                if (filteredProducts.includes(p)) {
                    p.style.display = "block";
                    setTimeout(() => p.classList.add("show"), 50);
                } else {
                    p.style.display = "none";
                }
            });
        }, 200);
    }

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            buttons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            const category = btn.dataset.category;
            const filteredProducts = Array.from(products).filter(p => {
                return category === "all" || p.dataset.category === category;
            });
            showProducts(filteredProducts);
        });
    });

    setTimeout(() => {
        products.forEach(p => p.classList.add("show"));
    }, 100);

    document.querySelectorAll(".btn-cart").forEach(btn => {
        btn.addEventListener("click", async (e) => {
            e.preventDefault();
            const url = btn.dataset.url;
            try {
                const res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });
                const data = await res.json();
                if (res.ok) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: data.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "error",
                        title: data.error,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan!",
                    text: "Silakan coba lagi.",
                });
            }
        });
    });
});
</script>

@endsection
