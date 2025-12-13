<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\KasirController;


/*
|--------------------------------------------------------------------------
| ✉️ VERIFIKASI EMAIL (DEFAULT LARAVEL)
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| ✅ VERIFIKASI EMAIL MANUAL DARI TOKEN
|--------------------------------------------------------------------------
*/
Route::get('/verify', [AuthController::class, 'verify'])->name('verify');
Route::get('/verify-success', [AuthController::class, 'verifySuccess'])->name('verify.success');

/*
|--------------------------------------------------------------------------
| 🌟 LANDING PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| 🧍 REGISTER & LOGIN CUSTOMER
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

/*
|--------------------------------------------------------------------------
| 👷 REGISTER & LOGIN PEKERJA
|--------------------------------------------------------------------------
*/
Route::get('/register/pekerja', [AuthController::class, 'showRegisterPekerja'])->name('register.pekerja');
Route::post('/register/pekerja', [AuthController::class, 'registerPekerja'])->name('register.pekerja.post');

Route::get('/login/pekerja', [AuthController::class, 'showLoginPekerja'])->name('login.pekerja');
Route::post('/login/pekerja', [AuthController::class, 'loginPekerja'])->name('login.pekerja.post');

/*
|--------------------------------------------------------------------------
| ☕ DASHBOARD CUSTOMER (WAJIB LOGIN & VERIFIKASI EMAIL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 📊 Dashboard utama
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

    // 🛒 Keranjang & Checkout
    Route::post('/cart/add/{id}', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart.view');
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/cart/update/{id}', [CustomerController::class, 'updateCart'])->name('cart.update');


    // ⚡ Pesanan & Riwayat
    Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders'); // Pesanan aktif
    Route::get('/history', [CustomerController::class, 'history'])->name('customer.history'); // Riwayat pesanan

    // ✅ Hitung keranjang (floating cart icon)
    Route::get('/cart/count', [CustomerController::class, 'cartCount'])->name('cart.count');

    /*
    |--------------------------------------------------------------------------
    | 💳 PEMBAYARAN (DINAMIS)
    |--------------------------------------------------------------------------
    */
    Route::get('/pembayaran/qris/{id}', [CustomerController::class, 'showQris'])->name('customer.qris');
    Route::get('/pembayaran/transfer/{id}', [CustomerController::class, 'showTransfer'])->name('customer.transfer');
    Route::get('/pembayaran/tunai/{kode}', [CustomerController::class, 'showTunai'])->name('customer.tunai');
});

/*
|--------------------------------------------------------------------------
| 🧑‍💼 DASHBOARD ADMIN & CRUD PRODUK
|--------------------------------------------------------------------------
*/
    Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::middleware(['auth'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');
});

    // CRUD Produk
    Route::get('/produk', [AdminController::class, 'produk'])->name('admin.produk');
    Route::get('/produk/tambah', [AdminController::class, 'tambahProduk'])->name('admin.produk.tambah');
    Route::post('/produk/simpan', [AdminController::class, 'simpanProduk'])->name('admin.produk.simpan');
    Route::get('/produk/edit/{id}', [AdminController::class, 'editProduk'])->name('admin.produk.edit');
    Route::put('/produk/update/{id}', [AdminController::class, 'updateProduk'])->name('admin.produk.update');
    Route::delete('/produk/hapus/{id}', [AdminController::class, 'hapusProduk'])->name('admin.produk.hapus');

    // Pesanan & Metode Pembayaran
    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('admin.pesanan');
    Route::get('/metode', [AdminController::class, 'metode'])->name('admin.metode');

    Route::post('/admin/konfirmasi-tunai', [AdminController::class, 'konfirmasiTunai'])->name('admin.konfirmasi.tunai');
    // 💰 Verifikasi Pembayaran Tunai (Kasir)
    Route::prefix('kasir')->middleware(['auth'])->group(function () {
    Route::get('/verifikasi', [AdminController::class, 'showVerifikasi'])->name('kasir.verifikasi');
    Route::post('/verifikasi', [AdminController::class, 'prosesVerifikasi'])->name('kasir.verifikasi.post');
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/pesanan', [AdminController::class, 'pesanan'])->name('admin.pesanan');
    Route::put('/admin/pesanan/{id}/status', [AdminController::class, 'ubahStatus'])->name('admin.ubahStatus');
});


});

/*
|--------------------------------------------------------------------------
| 🚪 LOGOUT (UNTUK SEMUA USER)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
