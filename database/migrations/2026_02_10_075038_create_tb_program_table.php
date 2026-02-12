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
        Schema::create('tb_program', function (Blueprint $table) {
            $table->id('id_program');
            $table->string('program', 200);
            $table->string('kegiatan', 200);
            $table->string('sub_kegiatan', 200);
            $table->string('kode_rekening', 50);
            $table->unsignedBigInteger('id_pegawai');
            
            // Foreign key constraint
            $table->foreign('id_pegawai')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            
            // Indexes
            $table->index('kode_rekening');
            $table->index('id_pegawai');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_program');
    }
};