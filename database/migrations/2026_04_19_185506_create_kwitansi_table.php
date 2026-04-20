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
        Schema::create('kwitansi', function (Blueprint $table) {
            $table->id();
            
            // Informasi Anggaran
            $table->string('tahun_anggaran', 4);
            $table->string('kode_rekening', 50);
            $table->string('sub_kegiatan');
            
            // Nomor Dokumen
            $table->string('no_bku', 100);
            $table->string('no_brpp', 100)->nullable();
            
            // Detail Kwitansi
            $table->string('terbilang');
            $table->text('untuk_pembayaran');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_kwitansi');
            
            // Pengguna Anggaran (Kepala Dinas)
            $table->string('pengguna_anggaran');
            $table->string('nip_pengguna_anggaran', 50);
            
            // Bendahara Pengeluaran
            $table->string('bendahara_pengeluaran');
            $table->string('nip_bendahara', 50);
            
            // Penerima (Pegawai yang menerima uang)
            $table->string('penerima');
            $table->string('nip_penerima', 50);
            
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
            
            // Index untuk pencarian
            $table->index(['tahun_anggaran', 'kode_rekening']);
            $table->index('tanggal_kwitansi');
            $table->index('no_bku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
    }
};