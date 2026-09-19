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
        if (!Schema::hasTable('set_harga_obat')) {
            Schema::create('set_harga_obat', function (Blueprint $table) {
                $table->enum('setharga', ['Umum', 'Per Jenis', 'Per Barang']);
                $table->enum('hargadasar', ['Harga Beli', 'Harga Diskon']);
                $table->enum('ppn', ['Yes', 'No']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_harga_obat');
    }
};
