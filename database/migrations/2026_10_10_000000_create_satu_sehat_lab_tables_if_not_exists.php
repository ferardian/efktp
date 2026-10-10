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
        if (!Schema::hasTable('satu_sehat_mapping_lab')) {
            Schema::create('satu_sehat_mapping_lab', function (Blueprint $table) {
                $table->integer('id_template')->primary();
                $table->string('code', 20)->nullable();
                $table->string('system', 100)->default('http://loinc.org');
                $table->string('display', 150)->nullable();
                $table->string('sampel_code', 20)->default('119297000');
                $table->string('sampel_system', 100)->default('http://snomed.info/sct');
                $table->string('sampel_display', 150)->default('Blood specimen');
            });
        }

        if (!Schema::hasTable('satu_sehat_servicerequest_lab')) {
            Schema::create('satu_sehat_servicerequest_lab', function (Blueprint $table) {
                $table->string('noorder', 20);
                $table->string('kd_jenis_prw', 20);
                $table->integer('id_template');
                $table->string('id_servicerequest', 64)->nullable();

                $table->primary(['noorder', 'id_template'], 'pk_servicerequest_lab');
                $table->index('kd_jenis_prw');
                $table->index('id_template');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satu_sehat_servicerequest_lab');
        Schema::dropIfExists('satu_sehat_mapping_lab');
    }
};
