<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Kolom 'harga' sudah ada, jadi hanya tambahkan kolom notes
            if (!Schema::hasColumn('order_items', 'notes')) {
                $table->string('notes')->nullable()->after('subtotal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['notes']);
        });
    }
};
