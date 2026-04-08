<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('spd', function (Blueprint $table) {
            $table->id('id_spd');
            $table->string('nomor_surat', 100);
            $table->unsignedBigInteger('pengguna_anggaran')->nullable();
            $table->text('maksud_perjadin')->nullable();
            $table->enum('alat_transportasi', [
                'transportasi_darat',
                'transportasi_udara',
                'transportasi_darat_udara',
                'angkutan_darat',
                'kendaraan_dinas',
                'angkutan_umum'
            ])->nullable();
            $table->string('tempat_berangkat', 255)->nullable();
            $table->unsignedBigInteger('tempat_tujuan')->nullable();
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->integer('lama_perjadin')->nullable();
            $table->string('skpd', 100)->nullable();
            $table->string('kode_rek', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('tempat_dikeluarkan', 100)->nullable();
            $table->date('tanggal_dikeluarkan')->nullable();
            $table->timestamps();

            // Foreign key ke tabel tb_pegawai
            $table->foreign('pengguna_anggaran')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');

            // Foreign key ke tabel tb_daerah
            $table->foreign('tempat_tujuan')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spd');
    }
};
