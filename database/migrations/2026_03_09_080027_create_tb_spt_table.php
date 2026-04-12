<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            
            // ===== KOLOM UNTUK APPROVAL KADIS =====
            // PERBAIKAN: Status approval ditambah 'resubmitted'
            $table->enum('status_approval', ['pending', 'approved', 'rejected', 'resubmitted'])
                  ->default('pending');
            
            // Tanggal approval (saat kadis menyetujui/menolak)
            $table->timestamp('approved_at')->nullable();
            
            // ID user (kadis) yang melakukan approval
            $table->unsignedBigInteger('approved_by')->nullable();
            
            // Catatan/alasan jika ditolak
            $table->text('rejection_reason')->nullable();
            
            // ===== KOLOM UNTUK RESUBMIT (AJUKAN ULANG) =====
            // Waktu pengajuan ulang (saat pegawai mengajukan ulang SPT yang ditolak)
            $table->timestamp('resubmitted_at')->nullable();
            
            // ID user (pegawai) yang mengajukan ulang
            $table->unsignedBigInteger('resubmitted_by')->nullable();
            
            // Waktu terakhir diedit setelah ditolak (untuk tracking)
            $table->timestamp('last_edited_at')->nullable();
            
            // ===== KOLOM UNTUK TTD + STEMPEL (GABUNGAN) =====
            // SATU field untuk gambar tanda tangan + stempel yang sudah tumpang tindih
            $table->string('ttd_stempel_path')->nullable();
            
            // ===== KOLOM UNTUK VERIFIKASI DIGITAL (QR CODE / BARCODE) =====
            // Kode unik verifikasi (contoh: SPT-67B3F2-8472)
            $table->string('verification_code', 100)->unique()->nullable();
            
            // Hash SHA256 dari data surat untuk deteksi perubahan/keaslian
            $table->string('document_hash', 255)->nullable();
            
            // Waktu terakhir surat ini diverifikasi (scan QR Code)
            $table->timestamp('verified_at')->nullable();
            
            // Jumlah total verifikasi yang telah dilakukan
            $table->integer('verification_count')->default(0);
            
            $table->timestamps();

            // Foreign key ke tabel tb_pegawai
            $table->foreign('penanda_tangan')
                  ->references('id_pegawai')
                  ->on('tb_pegawai')
                  ->onDelete('set null');
                  
            // Foreign key ke tabel users (yang approve)
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Foreign key ke tabel users (yang resubmit)
            $table->foreign('resubmitted_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // ===== INDEX UNTUK PERFORMANCE =====
            $table->index('status_approval', 'idx_spt_status');
            $table->index('resubmitted_at', 'idx_spt_resubmitted');
            $table->index(['status_approval', 'resubmitted_at'], 'idx_spt_status_resubmitted');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spt');
    }
};