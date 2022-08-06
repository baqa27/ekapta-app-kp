<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisiPengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisi_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->integer('pengajuan_id')->unsigned()->change();
            $table->string('catatan');
            $table->string('lampiran')->nullable();
            $table->timestamps();
            $table->foreign('pengajuan_id')->references('id')->on('pengajuans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi_pengajuans');
    }
}
