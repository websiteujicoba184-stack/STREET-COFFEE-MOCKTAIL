<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * 📊 Dashboard Admin
     */
    public function dashboard()
    {
        $total_pesanan   = Order::count();
        $pesanan_pending = Order::where('status', 'pending')->count();
        $pesanan_proses  = Order::where('status', 'on_progress')->count();
        $pesanan_selesai = Order::where('status', 'selesai')->count();
        $total_produk    = Product::count();

        return view('admin.dashboard', compact(
            'total_pesanan',
            'pesanan_pending',
            'pesanan_proses',
            'pesanan_selesai',
            'total_produk'
        ));
    }

    /**
     * 📦 Daftar Pesanan (semua)
     */
    public function pesanan()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        $judul  = 'Semua Pesanan';
        $statusFilter = null;

        return view('admin.pesanan', compact('orders', 'judul', 'statusFilter'));
    }

    /**
     * 📦 Pesanan Pending
     * route name: admin.pesanan.pending
     */
    public function pesananPending()
    {
        $orders = Order::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $judul        = 'Pesanan Pending';
        $statusFilter = 'pending';

        return view('admin.pesanan', compact('orders', 'judul', 'statusFilter'));
    }

    /**
     * 🔄 Pesanan On Progress
     * route name: admin.pesanan.on_progress
     */
    public function pesananOnProgress()
    {
        $orders = Order::where('status', 'on_progress')
            ->orderBy('created_at', 'desc')
            ->get();

        $judul        = 'Pesanan Sedang Diproses';
        $statusFilter = 'on_progress';

        return view('admin.pesanan', compact('orders', 'judul', 'statusFilter'));
    }

    /**
     * ✅ Pesanan Selesai
     * route name: admin.pesanan.selesai
     */
    public function pesananSelesai()
    {
        $orders = Order::where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->get();

        $judul        = 'Pesanan Selesai';
        $statusFilter = 'selesai';

        return view('admin.pesanan', compact('orders', 'judul', 'statusFilter'));
    }

    /**
     * 🔁 Ubah Status Pesanan
     * route name: admin.ubahStatus
     */
    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,on_progress,selesai',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * 🧾 Kelola Metode Pembayaran
     */
    public function metode()
    {
        return view('admin.metode');
    }

    /**
     * 💰 Konfirmasi Pembayaran Tunai
     * route name: admin.konfirmasi.tunai
     */
    public function konfirmasiTunai(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Silakan sesuaikan logika ini dengan kebutuhanmu
        // Misalnya: kalau sudah dibayar tunai, statusnya langsung "selesai"
        $order->status = 'selesai';
        $order->save();

        return back()->with('success', 'Pembayaran tunai berhasil dikonfirmasi.');
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
     * ➕ Tambah Produk (form)
     */
    public function tambahProduk()
    {
        return view('admin.tambah_produk');
    }

    /**
     * 💾 Simpan Produk Baru
     */
    public function simpanProduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'kategori'    => 'required|string|max:50',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * ✏️ Edit Produk (form)
     */
    public function editProduk($id)
    {
        $produk = Product::findOrFail($id);
        return view('admin.edit_produk', compact('produk'));
    }

    /**
     * 💾 Update Produk
     */
    public function updateProduk(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'kategori'    => 'nullable|string|max:50',
            'deskripsi'   => 'nullable|string',
        ]);

        $produk = Product::findOrFail($id);

        $produk->update($request->all());

        return redirect()->route('admin.produk')
            ->with('success', '✅ Produk berhasil diperbarui!');
    }

    /**
     * ❌ Hapus Produk
     */
    public function hapusProduk($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * (Opsional) Store tambahan kalau mau dipakai
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string',
            'kategori'    => 'required|string',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * 📈 Total Penjualan Detail (Grafik + Pie + Top Produk)
     */
    public function penjualanDetail()
    {
        // Total Omset (hanya pesanan selesai)
        $totalOmset = Order::where('status', 'selesai')->sum('total_harga') ?? 0;

        // Total Modal (jika ada kolom modal di tabel products)
        $totalModal = 0;
        if (Schema::hasColumn('products', 'modal')) {
            $totalModal = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('SUM(order_items.jumlah * products.modal) as modal')
                ->value('modal') ?? 0;
        }

        $profit = $totalOmset - $totalModal;

        // Grafik penjualan 12 bulan terakhir
        $penjualanBulanan = collect();

        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);

            $sum = Order::where('status', 'selesai')
                ->whereBetween('created_at', [
                    $m->copy()->startOfMonth(),
                    $m->copy()->endOfMonth()
                ])
                ->sum('total_harga');

            $penjualanBulanan->push([
                'bulan' => $m->month,
                'total' => (int) $sum
            ]);
        }

        // Pie Chart Penjualan per Produk
        $penjualanProduk = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.nama_produk', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('products.nama_produk')
            ->orderByDesc('total')
            ->get();

        // Produk terlaris
        $bestSeller = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.nama_produk', DB::raw('SUM(order_items.jumlah) as qty'))
            ->groupBy('products.nama_produk')
            ->orderByDesc('qty')
            ->first();

        // Top buyer
        $topBuyers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('SUM(orders.total_harga) as total_spent'))
            ->where('orders.status', 'selesai')
            ->groupBy('users.name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('admin.penjualan_detail', compact(
            'totalOmset',
            'totalModal',
            'profit',
            'penjualanBulanan',
            'penjualanProduk',
            'bestSeller',
            'topBuyers'
        ));
    }
    public function report(Request $request)
{
    // Ambil input filter
    $start = $request->input('start_date');
    $end = $request->input('end_date');
    $payment = $request->input('metode_pembayaran');
    $cashier = $request->input('kasir');
    $search = $request->input('search');

    // Default periode: 30 hari terakhir jika tidak ada input
    $today = Carbon::today();
    $defaultStart = $today->copy()->subDays(29);
    $defaultEnd = $today;

    try {
        $startDate = $start ? Carbon::parse($start)->startOfDay() : $defaultStart->startOfDay();
        $endDate = $end ? Carbon::parse($end)->endOfDay() : $defaultEnd->endOfDay();
    } catch (\Exception $e) {
        return redirect()->route('admin.report')->with('error', 'Format tanggal tidak valid.');
    }

    // Validasi batasan: max 3 bulan dan hanya 3 tahun ke belakang
    if ($startDate->gt($endDate)) {
        return redirect()->route('admin.report')->with('error', 'Tanggal mulai harus sebelum tanggal akhir.');
    }

    $maxRangeDays = 92; // ~3 months
    if ($startDate->diffInDays($endDate) > $maxRangeDays) {
        return redirect()->route('admin.report')->with('error', 'Pilih rentang maksimal 3 bulan (sekali pencarian).');
    }

    $threeYearsAgo = Carbon::now()->subYears(3)->startOfDay();
    if ($startDate->lt($threeYearsAgo)) {
        return redirect()->route('admin.report')->with('error', 'Anda hanya dapat melihat data 3 tahun terakhir.');
    }

    // Query orders sesuai filter (ambil semua kolom + relasi items dan user)
    $ordersQuery = \App\Models\Order::with(['items.product', 'user'])
        ->whereBetween('created_at', [$startDate, $endDate]);

    // jika hanya ingin pesanan selesai/termasuk semua? biasanya laporan ambil 'selesai' dan 'on_progress' tergantung kebutuhan.
    // Di sini aku ambil semua status (tapi bila mau hanya selesai uncomment baris berikut)
    // $ordersQuery->where('status', 'selesai');

    if ($payment) {
        $ordersQuery->where('metode_pembayaran', $payment);
    }
    if ($cashier) {
        // Asumsi kolom user.nama di relasi user, dan kasir disimpan di orders.user_id
        $ordersQuery->whereHas('user', function($q) use ($cashier){
            $q->where('name', 'like', "%{$cashier}%");
        });
    }
    if ($search) {
        // cari dalam nama pemesan atau item produk
        $ordersQuery->where(function($q) use ($search) {
            $q->where('nama_pemesan', 'like', "%{$search}%")
              ->orWhereHas('items.product', function($q2) use ($search) {
                  $q2->where('nama_produk', 'like', "%{$search}%");
              });
        });
    }

    // Ambil daftar orders (paginate agar tidak berat)
    $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(100)->appends($request->all());

    // Ringkasan angka
    $totalTransactions = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])
        ->when($payment, fn($q) => $q->where('metode_pembayaran', $payment))
        ->when($cashier, fn($q) => $q->whereHas('user', fn($qq) => $qq->where('name', 'like', "%{$cashier}%")))
        ->count();

    $totalRevenue = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])
        ->when($payment, fn($q) => $q->where('metode_pembayaran', $payment))
        ->when($cashier, fn($q) => $q->whereHas('user', fn($qq) => $qq->where('name', 'like', "%{$cashier}%")))
        ->sum('total_harga');

    $totalItems = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->when($payment, fn($q) => $q->where('orders.metode_pembayaran', $payment))
        ->when($cashier, fn($q) => $q->where('users.name', 'like', "%{$cashier}%"))
        ->sum('order_items.jumlah');

    // rata-rata transaksi
    $avgTransaction = $totalTransactions ? (int) round($totalRevenue / max(1, $totalTransactions)) : 0;

    // profit (jika ada kolom modal di products)
    $totalModal = 0;
    if (Schema::hasColumn('products', 'modal')) {
        $totalModal = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('SUM(order_items.jumlah * products.modal) as modal')
            ->value('modal') ?? 0;
    }
    $profit = $totalRevenue - $totalModal;

    // Chart bar: total per-bulan (label dinamis sesuai rentang)
    $period = new \DatePeriod(
        new \DateTime($startDate->format('Y-m-01')),
        new \DateInterval('P1M'),
        (new \DateTime($endDate->format('Y-m-01')))->modify('+1 month')
    );

    $months = [];
    $totalsByMonth = [];
    foreach ($period as $dt) {
        $mStart = Carbon::parse($dt->format('Y-m-01'))->startOfMonth();
        $mEnd = $mStart->copy()->endOfMonth();
        $label = $mStart->format('M Y');
        $months[] = $label;

        $sum = \App\Models\Order::whereBetween('created_at', [$mStart, $mEnd])
            ->when($payment, fn($q) => $q->where('metode_pembayaran', $payment))
            ->when($cashier, fn($q) => $q->whereHas('user', fn($qq) => $qq->where('name', 'like', "%{$cashier}%")))
            ->sum('total_harga');

        $totalsByMonth[] = (int) $sum;
    }

    // Pie chart: penjualan (subtotal) per produk dalam rentang
    $penjualanProduk = DB::table('order_items')
        ->join('orders','order_items.order_id','=','orders.id')
        ->join('products','order_items.product_id','=','products.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->when($payment, fn($q) => $q->where('orders.metode_pembayaran', $payment))
        ->select('products.nama_produk', DB::raw('SUM(order_items.subtotal) as total'))
        ->groupBy('products.nama_produk')
        ->orderByDesc('total')
        ->get();

    // Best seller (qty)
    $bestSeller = DB::table('order_items')
        ->join('orders','order_items.order_id','=','orders.id')
        ->join('products','order_items.product_id','=','products.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->select('products.nama_produk', DB::raw('SUM(order_items.jumlah) as qty'))
        ->groupBy('products.nama_produk')
        ->orderByDesc('qty')
        ->first();

    // Top buyers (users)
    $topBuyers = DB::table('orders')
        ->join('users','orders.user_id','=','users.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->select('users.id','users.name', DB::raw('SUM(orders.total_harga) as total_spent'), DB::raw('COUNT(orders.id) as total_orders'))
        ->groupBy('users.id','users.name')
        ->orderByDesc('total_spent')
        ->limit(10)
        ->get();

    // Data kasir list (unique users from orders in range)
    $kasirs = DB::table('users')
        ->join('orders','orders.user_id','=','users.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->select('users.name')
        ->distinct()
        ->pluck('name');

    return view('admin.report', compact(
        'orders',
        'startDate',
        'endDate',
        'totalTransactions',
        'totalRevenue',
        'totalItems',
        'avgTransaction',
        'profit',
        'months',
        'totalsByMonth',
        'penjualanProduk',
        'bestSeller',
        'topBuyers',
        'kasirs',
        'payment',
        'cashier',
        'search'
    ));
}

}