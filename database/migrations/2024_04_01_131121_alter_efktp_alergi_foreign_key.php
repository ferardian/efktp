<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('efktp_alergi', function (Blueprint $table) {
			$table->dropForeign(['no_rkm_medis']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('efktp_alergi', function (Blueprint $table) {
			$table->foreign('no_rkm_medis')
				->references('no_rkm_medis')->on('pasien')
				->onDelete('Cascade')->onDelete('Cascade');
		});
	}
};
