<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tb_list')) {
            Schema::create('tb_list', function (Blueprint $table) {
                $table->char('kd_list', 15)->primary();
                $table->date('date_list')->index();
                $table->string('kd_layanan', 2)->nullable()->index();
                $table->string('antrian', 5);
                $table->integer('kd_loket')->nullable()->index();
                $table->enum('status', ['Print', 'Call', 'Finish'])->default('Print');
            });
        }

        // Buat trigger tb_list untuk auto numbering kd_list, date_list, status, dan antrian
        DB::unprepared("
            DROP TRIGGER IF EXISTS `tb_list`;
            CREATE TRIGGER `tb_list` BEFORE INSERT ON `tb_list` FOR EACH ROW BEGIN
            SET NEW.kd_list=(SELECT CONCAT(DATE_FORMAT(SYSDATE(),'%y%m%d%H%i%s'),REPEAT('0',3-LENGTH(CONVERT(IFNULL(MAX(RIGHT(kd_list,3)),0),UNSIGNED)+1)),CONVERT(IFNULL(MAX(RIGHT(kd_list,3)),0),UNSIGNED)+1) FROM tb_list WHERE LEFT(kd_list,12)=DATE_FORMAT(SYSDATE(),'%y%m%d%H%i%s'));
            SET NEW.date_list=DATE_FORMAT(SYSDATE(),'%Y-%m-%d');
            SET NEW.status='Print';
            SET NEW.antrian=(SELECT CONCAT(NEW.kd_layanan,IFNULL(COUNT(*),0)+1) FROM tb_list WHERE date_list=DATE_FORMAT(SYSDATE(),'%Y-%m-%d') AND kd_layanan=NEW.kd_layanan);
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS `tb_list`;");
        Schema::dropIfExists('tb_list');
    }
};
