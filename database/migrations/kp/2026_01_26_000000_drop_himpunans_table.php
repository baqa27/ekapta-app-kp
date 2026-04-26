<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghapus tabel himpunans yang duplikat.
     * Sistem menggunakan himpunan_kps sesuai standar penamaan.
     */
    public function up(): void
    {
        // Cek apakah tabel sesi_seminar_kps ada (tabel KP)
        if (Schema::hasTable('sesi_seminar_kps')) {
            // 1. Drop foreign key constraint dari sesi_seminar_kps
            Schema::table('sesi_seminar_kps', function (Blueprint $table) {
                $table->dropForeign(['himpunan_id']);
            });
            
            // 2. Update foreign key untuk mereferensi himpunan_kps
            Schema::table('sesi_seminar_kps', function (Blueprint $table) {
                $table->foreign('himpunan_id')
                      ->references('id')
                      ->on('himpunan_kps')
                      ->onDelete('set null');
            });
        }
        
        // 3. Drop tabel himpunans jika ada
        Schema::dropIfExists('himpunans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu recreate karena tabel ini duplikat
        // Gunakan himpunan_kps sebagai gantinya
    }
};
