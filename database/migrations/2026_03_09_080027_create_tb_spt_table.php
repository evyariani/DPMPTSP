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
            $table->json('pegawai')->nullable(); // Menyimpan array ID pegawai
            $table->json('pegawai_snapshot')->nullable(); // Snapshot data pegawai
            $table->text('tujuan')->nullable();
            $table->date('tanggal');
            $table->string('lokasi', 255)->nullable();
            $table->unsignedBigInteger('penanda_tangan')->nullable();
            
            // Snapshot data penanda tangan
            $table->string('penanda_tangan_nama', 150)->nullable();
            $table->string('penanda_tangan_nip', 50)->nullable();
            $table->string('penanda_tangan_jabatan', 150)->nullable();
            
            $table->timestamps();

            // Foreign key ke tabel tb_pegawai
            $table->foreign('penanda_tangan')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');
                  
            // Index untuk performance
            $table->index('nomor_surat');
            $table->index('tanggal');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spt');
    }
};