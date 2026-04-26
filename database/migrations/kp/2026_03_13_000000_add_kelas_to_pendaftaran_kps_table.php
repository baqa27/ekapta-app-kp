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
        Schema::table('pendaftaran_kps', function (Blueprint $table) {
            // Add kelas column after jenis_mahasiswa
            // Values: 'Reguler' or 'B' (for Karyawan)
            $table->string('kelas')->nullable()->after('jenis_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_kps', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });
    }
};
