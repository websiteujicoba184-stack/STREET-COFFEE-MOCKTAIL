<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * 📊 Dashboard Admin
     */
    public function dashboard()
    {
        $total_pesanan = Order::count();
        $pesanan_pending = Order::where('status', 'pending')->count();
        $pesanan_proses = Order::where('status', 'on_progress')->count();
        $pesanan_selesai = Order::where('status', 'selesai')->count();
        $total_produk = Product::count();

        return view('admin.dashboard', compact(
            'total_pesanan',
            'pesanan_pending',
            'pesanan_proses',
            'pesanan_selesai',
            'total_produk'
        ));
    }

    /**
     * 📦 Daftar Pesanan Masuk
     */
    public function pesanan()
{
    // Ambil semua pesanan dari database dengan relasi item & produk
    $orders = \App\Models\Order::with('items.product', 'user')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.pesanan', compact('orders'));
}


    /**
     * 🧾 Kelola Metode Pembayaran
     */
    public function metode()
    {
        return view('admin.metode');
    }

    /**
     * 🛍️ Daftar Produk
     */
    public function produk()
    {
        $produk = Product::all();
        return view('admin.produk', compact('produk'));
    }

    /**
     * ➕ Tambah Produk
     */
    public function tambahProduk()
    {
        return view('admin.tambah_produk');
    }

    public function simpanProduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'harga' => 'required|numeric',
        ]);

        Product::create($request->only(['nama_produk', 'kategori', 'harga']));

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * ✏️ Edit Produk
     */
    public function editProduk($id)
    {
        $produk = Product::findOrFail($id);
        return view('admin.edit_produk', compact('produk'));
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Product::findOrFail($id);
        $produk->update($request->only(['nama_produk', 'kategori', 'harga']));

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil diperbarui!');
    }

      public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama_produk' => 'required|string',
        'kategori' => 'required|string',
        'harga' => 'required|numeric',
        'stok' => 'required|integer|min:0',
    ]);

    $product = Product::findOrFail($id);
    $product->update($validated);

    return redirect()->route('admin.produk')->with('success', 'Produk berhasil diperbarui!');
}


    /**
     * ❌ Hapus Produk
     */
    public function hapusProduk($id)
    {
        $produk = Product::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil dihapus!');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_produk' => 'required|string',
        'kategori' => 'required|string',
        'harga' => 'required|numeric',
        'stok' => 'required|integer|min:0',
    ]);

    Product::create($validated);

    return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan!');
}

}
