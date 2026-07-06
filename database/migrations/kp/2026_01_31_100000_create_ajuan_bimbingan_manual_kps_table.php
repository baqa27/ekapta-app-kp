<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel ajuan_bimbingan_manual_kps
 * 
 * Tabel ini menyimpan pengajuan lembar bimbingan manual dari mahasiswa
 * Setiap pengiriman lembar bimbingan dianggap sebagai pengajuan dengan status pending
 * 
 * ALUR:
 * 1. Mahasiswa submit laporan (masuk ke bimbingan_kps dengan status pending)
 * 2. Mahasiswa upload foto lembar bimbingan offline ke tabel ini
 * 3. Admin/Prodi mereview dan ACC/Tolak
 * 4. Jika ACC, pengajuan sah dan masuk progres bimbingan
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajuan_bimbingan_manual_kps', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke bimbingan (laporan online)
            $table->foreignId('bimbingan_id')->constrained('bimbingan_kps')->onDelete('cascade');
            
            // Relasi ke mahasiswa
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            
            // File foto lembar bimbingan dari dosen
            $table->string('foto_lembar_bimbingan');
            
            // Tanggal bimbingan offline yang diinput mahasiswa
            $table->date('tanggal_bimbingan');
            
            // Status yang dipilih mahasiswa: 'revisi' atau 'acc'
            // Ini adalah pilihan awal dari mahasiswa berdasarkan hasil offline
            $table->enum('status_mahasiswa', ['revisi', 'acc'])->default('acc');
            
            // Status pengajuan: 'pending', 'acc', 'revisi', 'ditolak'
            // Status final ditentukan oleh admin/prodi
            $table->enum('status', ['pending', 'acc', 'revisi', 'ditolak'])->default('pending');
            
            // Catatan dari admin/prodi saat review
            $table->text('catatan_reviewer')->nullable();
            
            // ID reviewer (admin atau prodi)
            $table->unsignedBigInteger('reviewed_by')->nullable();
            
            // Tipe reviewer: 'admin' atau 'prodi'
            $table->enum('reviewer_type', ['admin', 'prodi'])->nullable();
            
            // Tanggal direview
            $table->timestamp('tanggal_review')->nullable();
            
            // Keterangan tambahan dari mahasiswa
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            
            // Index untuk query
            $table->index(['mahasiswa_id', 'status']);
            $table->index(['bimbingan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajuan_bimbingan_manual_kps');
    }
};
