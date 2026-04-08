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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            // 1. Barang apa yang diubah?
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // 2. Siapa manusia (Admin) yang mengubahnya?
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // 3. Status Pergerakan ('in' = Barang Masuk, 'out' = Buang/Rusak)
            $table->enum('type', ['in', 'out'])->default('in');
            $table->integer('quantity'); // Berapa jumlah barang yang diangkat/dibuang
            $table->string('description'); // Penjelasan (Contoh: "Bongkaran dari Supplier Indofood")
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
