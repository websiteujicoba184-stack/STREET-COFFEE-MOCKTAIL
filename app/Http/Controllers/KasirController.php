<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class KasirController extends Controller
{
    public function index()
    {
        $produks = Product::with('kategori')->get();
        return view('kasir.index', compact('produks'));
    }

    public function checkout(Request $request)
{
    $items = $request->input('items', []);
    $uang_dibayar = $request->input('uang_dibayar');
    $total = 0;

    foreach ($items as $item) {
        $product = Product::find($item['id']);
        if ($product) {
            $subtotal = $product->harga * $item['qty'];
            $total += $subtotal;

            // Kurangi stok
            $product->stok = max(0, $product->stok - $item['qty']);
            $product->save();
        }
    }

    $kembalian = $uang_dibayar - $total;

    // Buat order baru
    $order = Order::create([
        'nama_pemesan' => 'Kasir',
        'total_harga' => $total,
        'status' => 'selesai',
        'metode_pembayaran' => 'cash',
        'user_id' => auth()->id() ?? null,
    ]);

    // Simpan item per produk
    foreach ($items as $item) {
        $product = Product::find($item['id']);
        if ($product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'harga' => $product->harga, // 🟢 tambahkan ini
                'jumlah' => $item['qty'],
                'subtotal' => $product->harga * $item['qty'],
            ]);
        }
    }

    return redirect()->route('kasir.index')->with('success', "Transaksi berhasil! Kembalian: Rp " . number_format($kembalian, 0, ',', '.'));
}

    public function showConfirmCashPage(Request $request)
    {
        $search = $request->input('search');

        $pendingOrders = Order::where('metode_pembayaran', 'Tunai')
                              ->where('status', 'pending')
                              ->when($search, function ($query) use ($search) {
                                  return $query->where('kode_pembayaran', 'like', '%' . $search . '%');
                              })
                              ->with(['items.product'])
                              ->latest()
                              ->get();

        return view('kasir.confirm_cash', compact('pendingOrders', 'search'));
    }

    public function confirmCashPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('status', 'pending')
                      ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan atau sudah dikonfirmasi.');
        }

        $order->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pembayaran tunai berhasil dikonfirmasi.');
    }

}
