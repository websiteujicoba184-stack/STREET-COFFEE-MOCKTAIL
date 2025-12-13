<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CustomerController extends Controller
{
    /**
     * 🏠 Dashboard Customer
     */
 public function dashboard()
{
    $products = Product::all();
    $cartCount = Cart::where('user_id', Auth::id())->count();

    // Ambil daftar kategori unik dari produk
    $kategoriList = $products->pluck('kategori')->unique()->filter();

    return view('customer.dashboard', compact('products', 'cartCount', 'kategoriList'));
}

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->jumlah += 1;
            $cart->subtotal = $cart->jumlah * $product->harga;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'jumlah' => 1,
                'subtotal' => $product->harga,
            ]);
        }

        return redirect()->route('customer.dashboard')->with('info', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * 🧾 Lihat Keranjang
     */
    public function cart()
    {
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cart->sum('subtotal');

        return view('customer.cart', compact('cart', 'total'));
    }

    /**
     * 🔄 Update Jumlah Item di Keranjang (AJAX)
     */
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('product')
            ->firstOrFail();

        $action = $request->input('action');

        if ($action === 'plus') {
            $cart->jumlah += 1;
        } elseif ($action === 'minus') {
            $cart->jumlah -= 1;
            if ($cart->jumlah <= 0) {
                $cart->delete();
                $total = Cart::where('user_id', Auth::id())->sum('subtotal');
                return response()->json([
                    'success' => true,
                    'removed' => true,
                    'total' => $total
                ]);
            }
        }

        $cart->subtotal = $cart->jumlah * $cart->product->harga;
        $cart->save();

        $total = Cart::where('user_id', Auth::id())->sum('subtotal');

        return response()->json([
            'success' => true,
            'removed' => false,
            'jumlah' => $cart->jumlah,
            'subtotal' => $cart->subtotal,
            'total' => $total
        ]);
    }

    /**
     * 💳 Proses Checkout Pesanan
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'nama' => 'required|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        DB::beginTransaction();
        try {
            $total = $cartItems->sum(fn($item) => $item->jumlah * $item->product->harga);

            $kodePembayaran = null;
            if ($request->metode_pembayaran === 'Tunai') {
                $kodePembayaran = 'SCM-' . date('Y') . '-' . rand(1000, 9999);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'nama_pemesan' => $request->nama,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => 'pending',
                'total_harga' => $total,
                'kode_pembayaran' => $kodePembayaran,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->product->harga,
                    'subtotal' => $item->jumlah * $item->product->harga,
                    'notes' => $request->notes,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();
            DB::commit();

            // Arahkan ke halaman sesuai metode pembayaran
            if ($request->metode_pembayaran === 'Tunai') {
                return redirect()
                    ->route('customer.tunai', ['kode' => $order->kode_pembayaran])
                    ->with('info', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran di kasir.');
            }

            if ($request->metode_pembayaran === 'QRIS') {
                return redirect()
                    ->route('customer.qris', ['id' => $order->id])
                    ->with('info', 'Silakan selesaikan pembayaran melalui QRIS.');
            }

            if ($request->metode_pembayaran === 'Transfer') {
                return redirect()
                    ->route('customer.transfer', ['id' => $order->id])
                    ->with('info', 'Silakan lakukan transfer ke rekening yang tertera.');
            }

            return redirect()->route('customer.orders')->with('info', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 📦 Pesanan Saya (pending / on progress)
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'on_progress'])
            ->with(['items.product'])
            ->latest()
            ->get();

        return view('customer.orders', compact('orders'));
    }

    /**
     * 🧾 Riwayat Pesanan
     */
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['selesai', 'batal'])
            ->with(['items.product'])
            ->latest()
            ->get();

        return view('customer.history', compact('orders'));
    }

    /**
     * 🧮 Hitung jumlah item di keranjang (AJAX)
     */
    public function cartCount()
    {
        $count = Cart::where('user_id', auth()->id())->count();
        return response()->json(['count' => $count]);
    }

    /**
     * 💳 Halaman Pembayaran QRIS
     */
    public function showQris($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.qris', compact('order'));
    }

    /**
     * 💸 Halaman Pembayaran Transfer
     */
    public function showTransfer($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $rekening = [
            'bank' => 'BCA',
            'no_rekening' => '1234567890',
            'nama' => 'Street Coffee Mocktail',
        ];

        return view('customer.transfer', compact('order', 'rekening'));
    }

    /**
     * 💵 Halaman Pembayaran Tunai
     */
    public function showTunai($kode)
    {
        $order = Order::where('kode_pembayaran', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.tunai', compact('order'));
    }

    /**
     * ⚡ Simulasi Update Otomatis Status Pembayaran (QRIS / Transfer)
     */
    public function autoUpdatePembayaran($id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->metode_pembayaran, ['QRIS', 'Transfer']) && $order->status === 'pending') {
            $order->status = 'on_progress';
            $order->save();

            return redirect()->route('customer.orders')
                ->with('success', '✅ Pembayaran berhasil diverifikasi! Pesanan kamu sedang diproses ☕');
        }

        return redirect()->route('customer.orders')
            ->with('warning', 'Pembayaran tidak bisa diverifikasi otomatis.');
    }
}
