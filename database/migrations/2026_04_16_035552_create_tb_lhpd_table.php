<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_lhpd', function (Blueprint $table) {
            $table->id('id_lhpd');
            
            // ========== RELASI KE SPT ==========
            $table->unsignedBigInteger('spt_id')->nullable()->comment('ID SPT asal pembuatan LHPD');
            
            // ========== DATA DARI SPT (SNAPSHOT) ==========
            // Dasar (dari tb_spt - column dasar)
            $table->json('dasar')->nullable()->comment('Dasar perjalanan dari SPT');
            
            // Tujuan (dari tb_spt - column tujuan)
            $table->text('tujuan')->nullable()->comment('Tujuan perjalanan dari SPT');
            
            // Snapshot data pegawai (data lengkap saat SPT dibuat)
            $table->json('pegawai_snapshot')->nullable()->comment('Snapshot data pegawai yang melakukan perjalanan');
            
            // ========== DATA DARI SPD (SNAPSHOT) ==========
            // Tanggal berangkat (dari tb_spd - column tanggal_berangkat)
            $table->date('tanggal_berangkat')->nullable()->comment('Tanggal berangkat dari SPD');
            
            // Tempat tujuan (snapshot)
            $table->string('tempat_tujuan_snapshot', 255)->nullable()->comment('Snapshot nama tempat tujuan');
            
            // ID Daerah (referensi ke tb_daerah - untuk relasi, bukan untuk tampilan)
            $table->unsignedBigInteger('id_daerah')->nullable()->comment('ID daerah tujuan dari SPD (referensi)');
            
            // ========== DATA UANG HARIAN (SNAPSHOT) ==========
            $table->decimal('uang_harian_snapshot', 15, 0)->default(0)->comment('Snapshot uang harian per hari');
            $table->decimal('uang_transport_snapshot', 15, 0)->default(0)->comment('Snapshot uang transport per hari');
            $table->decimal('total_biaya_snapshot', 15, 0)->default(0)->comment('Snapshot total biaya perjalanan');
            
            // ========== DATA LHPD ==========
            $table->text('hasil')->nullable()->comment('Hasil Laporan Hasil Perjalanan Dinas');
            
            // Tempat LHPD dikeluarkan (referensi ke tb_daerah)
            $table->unsignedBigInteger('tempat_dikeluarkan')->nullable()->comment('Tempat LHPD dikeluarkan (referensi ke tb_daerah)');
            
            // Snapshot tempat dikeluarkan (agar tidak berubah)
            $table->string('tempat_dikeluarkan_snapshot', 255)->nullable()->comment('Snapshot nama tempat LHPD dikeluarkan');
            
            // Tanggal LHPD dibuat
            $table->date('tanggal_lhpd')->nullable()->comment('Tanggal LHPD dibuat');
            
            // Foto
            $table->json('foto')->nullable()->comment('Foto dokumentasi LHPD (multiple)');
            
            $table->timestamps();
            
            // ========== FOREIGN KEYS ==========
            // Foreign key ke tabel spt
            $table->foreign('spt_id')
                  ->references('id_spt')
                  ->on('spt')
                  ->onDelete('set null');
            
            // Foreign key ke tabel tb_daerah (untuk id_daerah dari SPD)
            $table->foreign('id_daerah')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('set null');
            
            // Foreign key ke tabel tb_daerah (untuk tempat LHPD dikeluarkan)
            $table->foreign('tempat_dikeluarkan')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('set null');
            
            // ========== INDEXES ==========
            $table->index('spt_id');
            $table->index('tanggal_berangkat');
            $table->index('id_daerah');
            $table->index('tempat_dikeluarkan');
            $table->index('tanggal_lhpd');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lhpd');
    }
};