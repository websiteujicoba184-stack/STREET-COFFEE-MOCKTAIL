<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'order_id',
        'product_id',
        'jumlah',
        'harga',
        'subtotal',
        'notes', // ✅ untuk catatan dari customer seperti “tambahkan es”, “tanpa gula”, dll
    ];

    /**
     * Relasi ke tabel products
     * Setiap item pesanan pasti terhubung ke 1 produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke tabel orders
     * Setiap item pesanan milik 1 order utama
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
