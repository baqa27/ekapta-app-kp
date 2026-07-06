<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * FIX BUG: Enable Input Manual - Status Langsung "Review"
     * 
     * MASALAH:
     * - Saat pendaftaran di-ACC, sistem otomatis membuat bimbingan kosong untuk setiap bagian
     * - Default value status = 'review' di migration bimbingan_kps_table
     * - Akibatnya semua bimbingan langsung berstatus "Review" padahal mahasiswa belum submit
     * 
     * SOLUSI:
     * 1. Ubah default value status dari 'review' menjadi null
     * 2. Update data lama yang statusnya 'review' tapi tanggal_bimbingan null menjadi status null
     */
    public function up(): void
    {
        // 1. Ubah default value status menjadi null
        Schema::table('bimbingan_kps', function (Blueprint $table) {
            $table->string('status')->nullable()->default(null)->change();
        });

        // 2. Fix data lama: Update bimbingan yang statusnya 'review' tapi tanggal_bimbingan null
        DB::table('bimbingan_kps')
            ->where('status', 'review')
            ->whereNull('tanggal_bimbingan')
            ->update(['status' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan default value status ke 'review'
        Schema::table('bimbingan_kps', function (Blueprint $table) {
            $table->string('status')->default('review')->change();
        });

        // Kembalikan data ke status 'review'
        DB::table('bimbingan_kps')
            ->whereNull('status')
            ->whereNull('tanggal_bimbingan')
            ->update(['status' => 'review']);
    }
};
