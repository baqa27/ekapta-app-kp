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
            if (!Schema::hasColumn('jilids', 'judul_artikel')) {
                $table->string('judul_artikel')->nullable()->after('kategori_jurnal');
            }
            if (!Schema::hasColumn('jilids', 'penulis')) {
                $table->string('penulis')->nullable()->after('judul_artikel');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jilids', function (Blueprint $table) {
            $table->dropColumn(['judul_artikel', 'penulis']);
        });
    }
};
