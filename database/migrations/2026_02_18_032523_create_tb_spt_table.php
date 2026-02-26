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
        Schema::create('tb_spt', function (Blueprint $table) {
            $table->id('id_spt'); // auto increment primary key
            $table->string('nomor_surat', 100); // untuk angka/simbol/huruf
            $table->text('dasar_surat'); // untuk menyimpan teks dengan multiple points
            $table->json('pegawai_yang_diperintahkan'); // menyimpan array id_pegawai dalam format JSON
            $table->text('untuk_keperluan'); // text bisa berisi angka/simbol
            $table->date('tanggal_surat_dibuat');
            $table->string('kota', 100); // tempat surat keluar
            $table->unsignedBigInteger('penandatangan_surat'); // foreign key ke tb_pegawai
            $table->timestamps(); // created_at dan updated_at
            
            // Foreign key constraint
            $table->foreign('penandatangan_surat')
                  ->references('id_pegawai') 
                  ->on('tb_pegawai')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_spt');
    }
};