<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Street Coffee Mocktail</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    
    body {
      background-color: #faf9f7;
      font-family: 'Poppins', sans-serif;
    }


    /* Navbar utama */
    .navbar {
      box-shadow: 0 3px 10px hsla(0, 22%, 75%, 0.00);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      font-weight: 600;
      color: #ffffffff !important;
    }

    .navbar-brand img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
      border: 2px solid white;
    }

    /* Sidebar muncul dari kanan */
    .sidebar {
      height: 100%;
      width: 260px;
      position: fixed;
      top: 0;
      right: -260px;
      backdrop-filter: blur(12px);
      padding-top: 60px;
      transition: 0.3s;
      z-index: 1050;
      box-shadow: -3px 0 10px rgba(0,0,0,0.3);
    }

    .sidebar.active {
      right: 0;
    }

    .sidebar a {
      padding: 12px 25px;
      text-decoration: none;
      font-size: 16px;
      color: #fff;
      display: block;
      transition: 0.2s;
    }

    .sidebar a:hover {
      background-color: #a97133;
      padding-right: 35px;
    }

    .overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: 1049;
    }

    .overlay.active {
      display: block;
    }

    footer {
      color: #777;
      margin-top: 30px;
      font-size: 14px;
    }
  </style>
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-dark px-3">
  <div class="d-flex align-items-center">
    <a class="navbar-brand" href="{{ route('customer.dashboard') }}">
      <img src="{{ asset('images/logo1.jpg') }}" alt="Logo SCM">
      Street Coffee Mocktail
    </a>
  </div>

  <button id="toggleSidebar" class="btn btn-outline-light">
    ☰
  </button>
</nav>

<!-- 🔹 Sidebar (kanan) -->
<div class="sidebar" id="sidebar">
  <center><p>SCM</p></center>
  <a href="{{ route('customer.dashboard') }}">☕ Menu</a>
  <a href="{{ route('cart.view') }}">🛒 Keranjang <span id="cartCount" class="badge bg-light text-dark ms-2">0</span></a>
  <a href="{{ route('customer.orders') }}">📦 Pesanan Saya</a>
  <a href="{{ route('customer.history') }}">🕒 Riwayat</a>

  <form action="{{ route('logout') }}" method="POST" class="mt-3 px-3">
    @csrf
    <button class="btn btn-danger w-100">Logout</button>
  </form>
</div>

<div class="overlay" id="overlay"></div>

<!-- 🔹 Konten -->
<div class="container mt-4">
  @yield('content')
</div>

<footer class="text-center">
  <p>© {{ date('Y') }} Street Coffee Mocktail</p>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const toggleSidebar = document.getElementById('toggleSidebar');

  toggleSidebar.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
  });

  // 🛒 Update jumlah keranjang otomatis
  function updateCartCount() {
    fetch("{{ route('cart.count') }}")
      .then(res => res.json())
      .then(data => {
        document.getElementById('cartCount').textContent = data.count;
      })
      .catch(err => console.error(err));
  }

  document.addEventListener("DOMContentLoaded", updateCartCount);
</script>
<!-- Floating Cart Button -->
<div id="floating-cart" 
     onclick="window.location.href='{{ route('cart.view') }}'"
     style="
        position: fixed;
        bottom: 25px;
        right: 25px;
        backdrop-filter: blur(12px);
        color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
     ">
  🛒
  <span id="cart-count" 
        style="
          position:absolute;
          top:5px; 
          right:5px; 
          background:red; 
          color:white;
          font-size:13px;
          border-radius:50%;
          padding:2px 7px;
          font-weight:bold;">
    0
  </span>
</div>

<script>
  // 🔁 Update jumlah keranjang setiap 5 detik
  async function updateCartCount() {
      try {
          const res = await fetch('{{ route('cart.count') }}');
          const data = await res.json();
          document.getElementById('cart-count').innerText = data.count;
      } catch (e) {
          console.error(e);
      }
  }
  setInterval(updateCartCount, 5000);
  updateCartCount();
</script>

<!-- ✅ Notifikasi sesuai konteks -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if (session('success'))
Swal.fire({
    title: '✅ Berhasil!',
    text: "{{ session('success') }}",
    icon: 'success',
    confirmButtonColor: '#5c3d2e',
    confirmButtonText: 'Oke ☕'
});
@endif

@if (session('error'))
Swal.fire({
    title: '❌ Gagal!',
    text: "{{ session('error') }}",
    icon: 'error',
    confirmButtonColor: '#d33',
    confirmButtonText: 'Coba Lagi'
});
@endif

@if (session('info'))
Swal.fire({
    title: 'ℹ️ Info',
    text: "{{ session('info') }}",
    icon: 'info',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Oke'
});
@endif
</script>


</body>
</html>
