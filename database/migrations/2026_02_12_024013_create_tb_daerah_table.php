<?php
// database/migrations/2024_01_01_000001_create_daerah_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_daerah', function (Blueprint $table) {
            $table->id(); // PAKAI id() BIASA, BUKAN id_daerah
            $table->string('kode', 20)->unique()->nullable();
            $table->string('nama', 100);
            $table->enum('tingkat', ['provinsi', 'kabupaten', 'kecamatan']);
            $table->foreignId('parent_id')->nullable()
                  ->constrained('tb_daerah') // PERBAIKI: tb_daerah, bukan daerah
                  ->nullOnDelete();
            $table->timestamps();
            
            $table->index(['tingkat', 'parent_id']);
            $table->index('nama');
            $table->index('kode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_daerah');
    }
};