<?php
// database/migrations/2024_01_01_000010_create_uang_harian_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_uang_harian', function (Blueprint $table) {
            $table->id('id_uang_harian');
            $table->unsignedBigInteger('daerah_id');
            $table->string('tempat_tujuan', 255);
            $table->decimal('uang_harian', 15, 0)->default(0);
            $table->decimal('uang_transport', 15, 0)->default(0);
            $table->decimal('total', 15, 0)->default(0);
            $table->timestamps();
            
            // Foreign key ke tb_daerah
            $table->foreign('daerah_id')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('restrict');
                  
            // Index untuk optimasi query
            $table->index('daerah_id');
            $table->index('tempat_tujuan');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_uang_harian');
    }
};