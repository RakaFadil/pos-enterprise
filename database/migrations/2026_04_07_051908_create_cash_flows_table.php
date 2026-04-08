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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            // Identitas siapa (Admin) pencatat uangnya
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Format standar Uang: 'in' = Pemasukan Tambahan, 'out' = Pengeluaran Kas
            $table->enum('type', ['in', 'out'])->default('in');

            // Decimal 15,2 artinya angka bisa triliunan (15 digit) + 2 angka di belakang koma murni akuntansi
            $table->decimal('amount', 15, 2);
            $table->string('description'); // Keterangan (Contoh: "Setoran Modal Kasir Pagi")

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
