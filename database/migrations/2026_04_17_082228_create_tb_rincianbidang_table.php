<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_rincianbidang', function (Blueprint $table) {
            $table->id();
            
            // ========== RELASI KE SPD ==========
            $table->unsignedBigInteger('spd_id');
            
            // ========== INFORMASI DASAR (dari SPD) ==========
            $table->string('nomor_sppd')->nullable()->comment('Nomor SPD (otomatis dari spd.nomor_surat)');
            $table->date('tanggal_berangkat')->nullable()->comment('Dari spd.tanggal_berangkat');
            $table->date('tanggal_kembali')->nullable()->comment('Dari spd.tanggal_kembali');
            $table->integer('lama_perjadin')->nullable()->comment('Dari spd.lama_perjadin');
            
            // ========== TUJUAN PERJALANAN (dari SPD) ==========
            $table->unsignedBigInteger('tempat_tujuan_id')->nullable()->comment('ID daerah tujuan dari spd.tempat_tujuan');
            $table->string('tempat_tujuan')->nullable()->comment('Nama tempat tujuan (denormalisasi)');
            
            // ========== BENDAHARA PENGELUARAN ==========
            $table->unsignedBigInteger('bendahara_pengeluaran_id')->nullable()->comment('Bendahara pengeluaran (referensi ke tb_pegawai)');
            
            // ========== SNAPSHOT BENDAHARA ==========
            $table->string('bendahara_nama', 150)->nullable()->comment('Nama bendahara pengeluaran (snapshot)');
            $table->string('bendahara_nip', 50)->nullable()->comment('NIP bendahara pengeluaran (snapshot)');
            $table->string('bendahara_jabatan', 150)->nullable()->comment('Jabatan bendahara pengeluaran (snapshot)');
            
            // ========== DATA UANG HARIAN (dari tb_uang_harian) ==========
            $table->unsignedBigInteger('uang_harian_id')->nullable()->comment('ID dari tb_uang_harian');
            $table->decimal('uang_harian', 15, 0)->default(0)->comment('Biaya harian per hari');
            
            // ========== PERHITUNGAN BIAYA (HANYA UANG HARIAN) ==========
            // Uang transport TIDAK dimasukkan di sini, akan dikwitansi terpisah
            $table->bigInteger('total')->default(0)->comment('Total biaya: uang_harian * lama_perjadin');
            
            // ========== LAIN-LAIN ==========
            $table->text('terbilang')->nullable()->comment('Total dalam bentuk terbilang');
            
            // ========== JSON UNTUK PEGAWAI (pelaksana) ==========
            $table->json('pegawai')->nullable()->comment('JSON berisi daftar pegawai pelaksana');
            
            $table->timestamps();
            
            // ========== FOREIGN KEYS ==========
            $table->foreign('spd_id')
                  ->references('id_spd')
                  ->on('spd')
                  ->onDelete('cascade');
            
            $table->foreign('tempat_tujuan_id')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('set null');
            
            $table->foreign('bendahara_pengeluaran_id')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');
            
            $table->foreign('uang_harian_id')
                  ->references('id_uang_harian')
                  ->on('tb_uang_harian')
                  ->onDelete('set null');
            
            // ========== INDEXES ==========
            $table->index('spd_id');
            $table->index('nomor_sppd');
            $table->index('tempat_tujuan_id');
            $table->index('bendahara_pengeluaran_id');
            $table->index('uang_harian_id');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rincianbidang');
    }
};