<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('efktp_paket_obat_umum', function (Blueprint $table) {
			$table->id()->startingValue(1000);
			$table->foreignId('paket_id')->references('id')->on('efktp_paket_obat')
				->cascadeOnDelete()
				->cascadeOnUpdate();
			$table->string('kode_brng')->collation('latin1_swedish_ci');
			$table->integer('jumlah');
			$table->string('aturan_pakai')->default('-');

			$table->foreign('kode_brng')->references('kode_brng')->on('databarang')
				->cascadeOnDelete()
				->cascadeOnUpdate();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('efktp_paket_obat_umum');
	}
};
