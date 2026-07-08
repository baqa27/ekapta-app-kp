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
        Schema::table('seminar_kps', function (Blueprint $table) {
            if (Schema::hasColumn('seminar_kps', 'file_pengesahan')) {
                $table->renameColumn('file_pengesahan', 'file_bimbingan');
            }
            if (Schema::hasColumn('seminar_kps', 'dokumen_penilaian')) {
                $table->dropColumn('dokumen_penilaian');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_kps', function (Blueprint $table) {
            if (Schema::hasColumn('seminar_kps', 'file_bimbingan')) {
                $table->renameColumn('file_bimbingan', 'file_pengesahan');
            }
            $table->string('dokumen_penilaian')->nullable()->after('link_akses_produk');
        });
    }
};
