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
        Schema::table('jilids', function (Blueprint $table) {
            if (Schema::hasColumn('jilids', 'berita_acara')) {
                $table->dropColumn('berita_acara');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jilids', function (Blueprint $table) {
            $table->string('berita_acara')->nullable();
        });
    }
};
