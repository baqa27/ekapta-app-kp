<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ⚠️ PERINGATAN: Migration ini mengubah tabel TA (mahasiswas)
     * 
     * Alasan: Kolom prodi_id diperlukan untuk relasi mahasiswa-prodi di KP
     * Aman karena: Nullable (tidak break data lama) + backward compatible
     * 
     * HANYA jalankan jika:
     * 1. Sudah backup database
     * 2. Sudah test di local
     * 3. Koordinasi dengan tim TA
     */
    public function up(): void
    {
        // Cek apakah kolom sudah ada (untuk avoid error saat re-run)
        if (!Schema::hasColumn('mahasiswas', 'prodi_id')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                // Tambah kolom prodi_id sebagai foreign key ke tabel prodis
                // Nullable untuk backward compatibility dengan data lama
                $table->unsignedBigInteger('prodi_id')->nullable()->after('thmasuk');
                
                // Foreign key constraint
                $table->foreign('prodi_id')->references('id')->on('prodis')->onDelete('set null');
                
                // Index untuk performa query
                $table->index('prodi_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('mahasiswas', 'prodi_id')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->dropForeign(['prodi_id']);
                $table->dropIndex(['prodi_id']);
                $table->dropColumn('prodi_id');
            });
        }
    }
};
