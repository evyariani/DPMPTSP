<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPegawaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nama', 100);
            $table->string('nip', 50)->nullable()->unique();
            $table->string('pangkat', 50)->nullable();
            $table->string('gol', 10)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('tk_jalan', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_pegawai');
    }
}