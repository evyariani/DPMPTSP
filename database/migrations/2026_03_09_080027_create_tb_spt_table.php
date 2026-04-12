<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spt', function (Blueprint $table) {
            $table->id('id_spt');
            $table->string('nomor_surat', 100);
            $table->json('dasar')->nullable();
            $table->json('pegawai')->nullable();
            $table->text('tujuan')->nullable();
            $table->date('tanggal');
            $table->string('lokasi', 255)->nullable();
            $table->unsignedBigInteger('penanda_tangan')->nullable();
            $table->timestamps();

            // Foreign key ke tabel tb_pegawai
            $table->foreign('penanda_tangan')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spt');
    }
};