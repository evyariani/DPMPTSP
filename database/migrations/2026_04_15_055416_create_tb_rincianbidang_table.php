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

            $table->string('nomor');
            $table->date('tanggal');
            $table->string('tujuan');

            $table->json('pegawai'); // JSON pegawai

            $table->integer('transport')->default(0);
            $table->bigInteger('total')->default(0);

            $table->text('terbilang')->nullable();

            $table->timestamps(); // wajib untuk latest()
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_rincianbidang');
    }
};