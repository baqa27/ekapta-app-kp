<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('tanggal_pembuatan_ta');
            $table->string('tanggal_acc_pembimbing_utama');
            $table->string('tanggal_acc_pembimbing_pendamping');
            $table->string('lampiran_1');
            $table->string('lampiran_2');
            $table->string('lampiran_3');
            $table->string('lampiran_4');
            $table->string('lampiran_5');
            $table->string('link_video');
            $table->integer('nilai')->default(0);
            $table->timestamps();
            $table->integer('dosen_id')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seminars');
    }
}