<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueNidnFromDekansTable extends Migration
{
    public function up()
    {
        Schema::table('dekans', function (Blueprint $table) {
            $table->dropUnique(['nidn']);
        });
    }

    public function down()
    {
        Schema::table('dekans', function (Blueprint $table) {
            $table->unique('nidn');
        });
    }
}
