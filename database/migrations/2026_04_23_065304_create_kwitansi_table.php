<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kwitansi', function (Blueprint $table) {
            $table->id();
            
            // ========== RELASI KE SPD ==========
            $table->unsignedBigInteger('spd_id');
            
            // ========== DATA DARI SPD ==========
            $table->string('tahun_anggaran', 4)->nullable();
            $table->string('kode_rekening', 100)->nullable();
            $table->text('sub_kegiatan')->nullable();
            $table->text('untuk_pembayaran')->nullable();
            
            // ========== DATA KWITANSI ==========
            $table->string('no_bku', 100)->nullable();
            $table->string('no_brpp', 100)->nullable();
            $table->text('terbilang')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->date('tanggal_kwitansi')->nullable();
            
            // ========== PENGGUNA ANGGARAN ==========
            $table->string('pengguna_anggaran', 255)->nullable();
            $table->string('nip_pengguna_anggaran', 50)->nullable();
            
            // ========== BENDAHARA PENGELUARAN ==========
            $table->string('bendahara_pengeluaran', 255)->nullable();
            $table->string('nip_bendahara', 50)->nullable();
            
            // ========== PENERIMA ==========
            $table->string('penerima', 255)->nullable();
            $table->string('nip_penerima', 50)->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // ========== FOREIGN KEYS ==========
            $table->foreign('spd_id')
                  ->references('id_spd')
                  ->on('spd')
                  ->onDelete('cascade');
            
            // ========== INDEXES ==========
            $table->index('spd_id');
            $table->index('no_bku');
            $table->index('kode_rekening');
            $table->index('tahun_anggaran');
            $table->index('tanggal_kwitansi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
    }
};