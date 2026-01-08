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
		Schema::create('efktp_hasil_usg', function (Blueprint $table) {
			$table->string('no_rawat', 17)->collation('latin1_swedish_ci')->primary();
			$table->string('kd_dokter', 20)->collation('latin1_swedish_ci');


			$table->date('tgl_periksa')->nullable()->default('1971-12-31');
			$table->time('jam_periksa')->nullable()->default('23:59:59');
			$table->string('janin', 200)->nullable();
			$table->string('presentasi', 200)->nullable();
			$table->string('presentasi2', 200)->nullable();
			$table->string('DJJ', 200)->nullable();
			$table->string('DJJ2', 200)->nullable();
			$table->string('letak_punggung', 200)->nullable();
			$table->string('letak_punggung2', 200)->nullable();
			$table->string('letak_plasenta', 200)->nullable();
			$table->string('letak_plasenta2', 200)->nullable();
			$table->enum('jenis_kelamin', ['L', 'P'])->nullable();
			$table->enum('jenis_kelamin2', ['L', 'P'])->nullable();
			$table->string('TBJ', 200)->nullable();
			$table->string('TBJ2', 200)->nullable();
			$table->string('kelainan_kongenital', 200)->nullable();
			$table->string('kelainan_kongenital2', 200)->nullable();
			$table->date('HPHT')->nullable();
			$table->date('HPL')->nullable();
			$table->date('HPL2')->nullable();
			$table->string('umur_kehamilan')->nullable();
			$table->string('umur_kehamilan2')->nullable();
			$table->string('GS', 200)->nullable();
			$table->string('lain_lain', 200)->nullable();
			$table->string('lain_lain2', 200)->nullable();
			$table->string('ketuban', 200)->nullable();
			$table->string('ketuban2', 200)->nullable();
			$table->text('pemeriksaan_fisik_tambahan')->nullable();
			$table->text('pemeriksaan_fisik_tambahan2')->nullable();
			$table->string('fetalpole', 200)->nullable();
			$table->string('pulsasi', 200)->nullable();
			$table->string('umur_kehamilan_gs', 200)->nullable();
			$table->string('lain', 200)->nullable();
			$table->timestamps();

			$table->foreign('kd_dokter')->references('kd_dokter')->on('dokter')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')
				->onDelete('cascade')
				->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('efktp_hasil_usg');
	}
};
