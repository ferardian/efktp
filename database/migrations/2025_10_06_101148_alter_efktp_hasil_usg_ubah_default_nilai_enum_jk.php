<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		// Ubah enum jenis_kelamin dan jenis_kelamin2 untuk menambahkan '-'
		DB::statement("ALTER TABLE efktp_hasil_usg MODIFY COLUMN jenis_kelamin ENUM('L','P','-') NULL DEFAULT NULL");
		DB::statement("ALTER TABLE efktp_hasil_usg MODIFY COLUMN jenis_kelamin2 ENUM('L','P','-') NULL DEFAULT NULL");
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		// Kembalikan enum ke semula (tanpa '-')
		DB::statement("ALTER TABLE efktp_hasil_usg MODIFY COLUMN jenis_kelamin ENUM('L','P') NULL DEFAULT NULL");
		DB::statement("ALTER TABLE efktp_hasil_usg MODIFY COLUMN jenis_kelamin2 ENUM('L','P') NULL DEFAULT NULL");
	}
};
