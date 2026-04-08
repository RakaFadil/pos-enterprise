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
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom manual "alasan" void/dibatalkan
            $table->string('void_reason')->nullable();

            // MAGIC LARAVEL: Menambahkan kolom deleted_at untuk sistem jejak history
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('void_reason');
            $table->dropSoftDeletes();
        });
    }
};
