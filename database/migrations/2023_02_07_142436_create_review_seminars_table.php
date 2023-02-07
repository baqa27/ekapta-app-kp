<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_seminars', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('tanggal_acc')->nullable();
            $table->string('dosen_status');
            $table->timestamps();
            $table->foreignId('seminar_id')->constrained();
            $table->foreignId('dosen_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_seminars');
    }
}
