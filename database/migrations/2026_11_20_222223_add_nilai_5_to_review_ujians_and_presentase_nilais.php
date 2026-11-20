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
        Schema::table('review_ujians', function (Blueprint $table) {
            if (!Schema::hasColumn('review_ujians', 'nilai_5')) {
                $table->integer('nilai_5')->nullable()->after('nilai_4');
            }
        });

        Schema::table('presentase_nilais', function (Blueprint $table) {
            if (!Schema::hasColumn('presentase_nilais', 'presentase_5')) {
                $table->integer('presentase_5')->nullable()->after('presentase_4');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_ujians', function (Blueprint $table) {
            $table->dropColumn('nilai_5');
        });

        Schema::table('presentase_nilais', function (Blueprint $table) {
            $table->dropColumn('presentase_5');
        });
    }
};
