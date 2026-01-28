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
        Schema::create('tb_rekening', function (Blueprint $table) {
            $table->id('id_rekening');
            $table->string('kode_rek', 20)->unique();
            $table->string('nomor_rek', 50)->unique();
            $table->string('uraian', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_rekening');
    }
};