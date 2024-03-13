<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJilidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jilids', function (Blueprint $table) {
            $table->id();
            $table->integer('total_pembayaran')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jilids');
    }
}
