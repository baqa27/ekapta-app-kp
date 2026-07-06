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
        Schema::table('presentase_nilai_kps', function (Blueprint $table) {
            $table->integer('bobot_instansi')->default(30)->after('bobot_pembimbing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presentase_nilai_kps', function (Blueprint $table) {
            $table->dropColumn('bobot_instansi');
        });
    }
};
