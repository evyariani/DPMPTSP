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
            
            // Dasar (dari tb_spt - column dasar)
            $table->json('dasar')->nullable()->comment('Dasar perjalanan dari SPT');
            
            // Tujuan (dari tb_spt - column tujuan)
            $table->text('tujuan')->nullable()->comment('Tujuan perjalanan dari SPT');
            
            // ID Pegawai (dari tb_spt - column pegawai yang berisi JSON)
            // Karena pegawai di spt berupa JSON, kita simpan sebagai JSON juga
            $table->json('id_pegawai')->nullable()->comment('ID pegawai yang melakukan perjalanan dari SPT');
            
            // Tanggal berangkat (dari tb_spd - column tanggal_berangkat)
            $table->date('tanggal_berangkat')->nullable()->comment('Tanggal berangkat dari SPD');
            
            // ID Daerah (dari tb_spd - column tempat_tujuan)
            $table->unsignedBigInteger('id_daerah')->nullable()->comment('ID daerah tujuan dari SPD');
            
            // Hasil LHPD
            $table->text('hasil')->nullable()->comment('Hasil Laporan Hasil Perjalanan Dinas');
            
            // Tempat LHPD dikeluarkan (references ke id_daerah di tb_daerah)
            $table->unsignedBigInteger('tempat_dikeluarkan')->nullable()->comment('Tempat LHPD dikeluarkan (referensi ke tb_daerah)');
            
            // Tanggal LHPD dibuat
            $table->date('tanggal_lhpd')->nullable()->comment('Tanggal LHPD dibuat');
            
            // Foto
            // Di file migration tb_lhpd, ubah kolom foto menjadi:
            $table->json('foto')->nullable()->comment('Foto dokumentasi LHPD (multiple)');  
            
            $table->timestamps();
            
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
            
            // Indexes untuk performance
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