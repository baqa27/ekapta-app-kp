<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosenMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dosen_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->integer('nim')->unsigned()->unique();
            $table->integer('dosbim_utama')->unsigned();
            $table->integer('dosbim_pendamping')->unsigned();
            $table->integer('dosen_penguji')->unsigned();
            $table->timestamps();
            $table->foreign('nim')->references('nim')->on('mahasiswas');
            $table->foreign('dosbim_utama')->references('nidn')->on('dosens');
            $table->foreign('dosbim_pendamping')->references('nidn')->on('dosens');
            $table->foreign('dosen_penguji')->references('nidn')->on('dosens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dosen_mahasiswas');
    }
}
