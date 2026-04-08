<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah Harga Modal di Tabel Master Produk
        Schema::table('products', function (Blueprint $table) {
            // diletakkan setelah kolom 'price' (harga jual)
            $table->decimal('harga_modal', 10, 2)->default(0)->after('price');
        });

        // 2. Tambah Hasil Laba di Tabel Detail Transaksi
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('harga_modal', 10, 2)->default(0)->after('harga_satuan');
            $table->decimal('profit', 10, 2)->default(0)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('harga_modal');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn(['harga_modal', 'profit']);
        });
    }
};
