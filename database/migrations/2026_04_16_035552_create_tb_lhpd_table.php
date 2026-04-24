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
            // Dasar TIDAK diambil dari SPT, ini inputan biasa di LHPD
            $table->json('dasar')->nullable()->comment('Dasar perjalanan (input manual LHPD, bisa lebih dari satu)');
            
            // Tujuan (dari tb_spt - snapshot)
            $table->text('tujuan')->nullable()->comment('Tujuan perjalanan dari SPT');
            
            // Snapshot data pegawai
            $table->json('pegawai_snapshot')->nullable()->comment('Snapshot data pegawai yang melakukan perjalanan');
            
            // ========== DATA DARI SPD (SNAPSHOT) ==========
            $table->date('tanggal_berangkat')->nullable()->comment('Tanggal berangkat dari SPD');
            $table->string('tempat_tujuan_snapshot', 255)->nullable()->comment('Snapshot nama tempat tujuan');
            $table->unsignedBigInteger('id_daerah')->nullable()->comment('ID daerah tujuan dari SPD (referensi)');
            
            // ========== DATA UANG HARIAN (SNAPSHOT) ==========
            $table->decimal('uang_harian_snapshot', 15, 0)->default(0);
            $table->decimal('uang_transport_snapshot', 15, 0)->default(0);
            $table->decimal('total_biaya_snapshot', 15, 0)->default(0);
            
            // ========== DATA LHPD ==========
            $table->text('hasil')->nullable();
            $table->unsignedBigInteger('tempat_dikeluarkan')->nullable();
            $table->string('tempat_dikeluarkan_snapshot', 255)->nullable();
            $table->date('tanggal_lhpd')->nullable();
            $table->json('foto')->nullable();
            
            $table->timestamps();
            
            // ========== FOREIGN KEYS ==========
            $table->foreign('spt_id')
                  ->references('id_spt')
                  ->on('spt')
                  ->onDelete('set null');
            
            $table->foreign('id_daerah')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('set null');
            
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lhpd');
    }
};