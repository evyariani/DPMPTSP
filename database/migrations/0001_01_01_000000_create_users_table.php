<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('password', 255);
            $table->enum('level', ['admin', 'pegawai', 'pemimpin', 'admin_keuangan'])->default('pegawai');
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};