<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->text('icon')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order_num')->default(0);
            $table->string('target')->default('_self');
            $table->string('position')->default('navbar'); // 'navbar' atau 'sidebar'
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });

        Schema::create('menu_role', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id');
            $table->string('role');
            $table->primary(['menu_id', 'role']);

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // NIK user
            $table->string('role');             // 'admin', 'dokter', 'apoteker', 'petugas', 'owner'
            $table->timestamps();
        });

        // Auto-seed default menus and roles if menus table is empty
        if (Schema::hasTable('menus') && \Illuminate\Support\Facades\DB::table('menus')->count() === 0) {
            (new \Database\Seeders\MenuSeeder())->run();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('menus');
    }
};
