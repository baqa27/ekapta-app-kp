<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('revisi_jilids')) {
            return;
        }

        Schema::create('revisi_jilids', function (Blueprint $table) {
            $table->id();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreignId('jilid_id')->constrained('jilids')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi_jilids');
    }
};
