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
        Schema::create('bridging_pcare_setting', function (Blueprint $table) {
            $table->id();
            $table->string('consId', 10);
            $table->string('secretKey', 200);
            $table->text('userKey');
            $table->string('user', 200);
            $table->string('password', 200);
            $table->text('baseUrl');
            $table->text('service');
            $table->string('appCode', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bridging_pcare_setting');
    }
};
