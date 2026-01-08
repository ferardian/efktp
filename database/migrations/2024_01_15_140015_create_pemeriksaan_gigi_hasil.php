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
		Schema::create('efktp_pemeriksaan_gigi_hasil', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->charset = 'latin1';
			$table->collation = 'latin1_swedish_ci';
			$table->id();
			$table->string('no_rawat', 20)->index();
			$table->integer('posisi_gigi', false, true);
			$table->string('hasil', 20);
			$table->string('kd_penyakit', 20)->index();
			$table->string('kd_tindakan', 20)->index();
			$table->string('keterangan', 200);
			$table->string('kd_dokter', 20)->index();
			$table->foreign('kd_penyakit')->references('kd_penyakit')->on('penyakit')->onDelete('Cascade')->onUpdate('Cascade');
			$table->foreign('kd_tindakan')->references('kode')->on('icd9')->onDelete('Cascade')->onUpdate('Cascade');
			$table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('Cascade')->onUpdate('Cascade');
			$table->foreign('kd_dokter')->references('kd_dokter')->on('dokter')->onDelete('Cascade')->onUpdate('Cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('efktp_pemeriksaan_gigi_hasil');
	}
};
