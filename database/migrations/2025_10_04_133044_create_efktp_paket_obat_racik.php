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
		Schema::create('efktp_paket_obat_racik', function (Blueprint $table) {
			$table->id()->startingValue(1000);

			$table->foreignId('paket_id')
				->constrained('efktp_paket_obat')
				->cascadeOnDelete()
				->cascadeOnUpdate();
			$table->integer('template_id');
			$table->integer('jumlah');
			$table->string('kd_racik')->collation('latin1_swedish_ci');
			$table->string('aturan_pakai')->default('-');

			$table->foreign('template_id')
				->references('id')
				->on('efktp_template_racikan')
				->cascadeOnUpdate()
				->cascadeOnDelete();

			$table->foreign('kd_racik')
				->references('kd_racik')
				->on('metode_racik')
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
		Schema::dropIfExists('efktp_paket_obat_racik');
	}
};
