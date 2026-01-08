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
        Schema::create('efktp_upload_penunjang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
            $table->id();
            $table->string('no_rawat', 17);
            $table->integer('id_kategori', 20)->autoIncrement(false);
            $table->text('file');
            $table->string('nik', 20);
            $table->timestamps();
            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')
                ->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreign('nik')->references('nik')->on('pegawai')
                ->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efktp_upload_penunjang');
    }
};
