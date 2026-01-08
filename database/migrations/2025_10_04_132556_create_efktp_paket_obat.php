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
		Schema::create('efktp_paket_obat', function (Blueprint $table) {
			$table->bigIncrements('id')->startingValue(1000);
			$table->string('kd_poli')->collation('latin1_swedish_ci');
			$table->string('nama', 200);
			$table->text('keterangan')->nullable();
			$table->boolean('aktif')->default(1);

			$table->foreign('kd_poli')->references('kd_poli')->on('poliklinik');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('efktp_paket_obat');
	}
};
