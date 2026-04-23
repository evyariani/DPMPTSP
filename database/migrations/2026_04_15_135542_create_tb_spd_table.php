<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        // Tabel utama SPD
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

            // ========== ATRIBUT BARU ==========
            // Relasi ke SPT (source)
            $table->unsignedBigInteger('spt_id')->nullable()->comment('ID SPT asal pembuatan SPD');

            // ========== SNAPSHOT PELAKSANA PERJALANAN DINAS ==========
            // HAPUS ->after() karena tidak support di create table
            $table->json('pelaksana_snapshot')->nullable()->comment('Snapshot data pelaksana perjalanan dinas');

            // Pejabat pelaksana teknis kegiatan (referensi ke tb_program)
            $table->unsignedBigInteger('pejabat_teknis_id')->nullable()->comment('ID program yang berisi pejabat teknis dan rekening');
            $table->unsignedBigInteger('pejabat_teknis_pegawai_id')->nullable()->comment('ID pegawai pejabat teknis dari tb_program');
            $table->string('pejabat_teknis_kode_rekening', 50)->nullable()->comment('Kode rekening dari tb_program');
            $table->string('pejabat_teknis_program', 200)->nullable()->comment('Program dari tb_program');
            $table->string('pejabat_teknis_kegiatan', 200)->nullable()->comment('Kegiatan dari tb_program');
            $table->string('pejabat_teknis_sub_kegiatan', 200)->nullable()->comment('Sub kegiatan dari tb_program');

            // ========== PENANDA TANGAN SPD (EKSTERNAL) ==========
            $table->string('penanda_tangan_nama', 150)->nullable()->comment('Nama pejabat penanda tangan di tempat tujuan');
            $table->string('penanda_tangan_nip', 50)->nullable()->comment('NIP pejabat penanda tangan');
            $table->string('penanda_tangan_jabatan', 150)->nullable()->comment('Jabatan pejabat penanda tangan');
            $table->string('penanda_tangan_instansi', 200)->nullable()->comment('Instansi/lembaga asal penanda tangan');

            $table->timestamps();

            // Foreign key ke tabel tb_pegawai (pengguna anggaran)
            $table->foreign('pengguna_anggaran')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');

            // Foreign key ke tabel tb_daerah (tempat tujuan)
            $table->foreign('tempat_tujuan')
                  ->references('id')
                  ->on('tb_daerah')
                  ->onDelete('set null');

            // Foreign key ke tabel tb_program (pejabat teknis)
            $table->foreign('pejabat_teknis_id')
                  ->references('id_program')
                  ->on('tb_program')
                  ->onDelete('set null');

            // Foreign key ke tabel tb_pegawai (pejabat teknis pegawai)
            $table->foreign('pejabat_teknis_pegawai_id')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');

            // Foreign key ke tabel spt
            $table->foreign('spt_id')
                  ->references('id_spt')
                  ->on('spt')
                  ->onDelete('set null');

            // Indexes untuk performance
            $table->index('pejabat_teknis_id');
            $table->index('pejabat_teknis_pegawai_id');
            $table->index('pejabat_teknis_kode_rekening');
            $table->index('spt_id');
            $table->index('penanda_tangan_nama');
            $table->index('penanda_tangan_nip');
            $table->index('created_at');
            $table->index('updated_at');
        });

        // Tabel pivot untuk pelaksana perjalanan dinas (many-to-many)
        Schema::create('spd_pelaksana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spd_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('spd_id')
                  ->references('id_spd')
                  ->on('spd')
                  ->onDelete('cascade');

            $table->foreign('pegawai_id')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('cascade');

            // Unique constraint untuk menghindari duplikasi
            $table->unique(['spd_id', 'pegawai_id']);

            // Indexes
            $table->index('spd_id');
            $table->index('pegawai_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spd_pelaksana');
        Schema::dropIfExists('spd');
    }
};
