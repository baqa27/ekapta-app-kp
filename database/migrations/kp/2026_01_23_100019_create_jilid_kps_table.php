<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jilid_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('status')->default('review');
            $table->string('status_arsip')->nullable();
            $table->timestamp('tanggal_arsip')->nullable();
            
            // Info KP
            $table->string('lokasi_kp')->nullable();
            $table->string('waktu_pelaksanaan_kp')->nullable();
            
            $table->string('laporan_word')->nullable();
            $table->string('laporan_pdf')->nullable();
            $table->string('lembar_pengesahan')->nullable();
            $table->string('file_project')->nullable();
            $table->string('berita_acara')->nullable();
            $table->string('panduan')->nullable();
            $table->decimal('nilai_pembimbing', 5, 2)->nullable();
            $table->decimal('nilai_penguji', 5, 2)->nullable();
            $table->decimal('nilai_instansi', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('bukti_nilai_instansi')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->decimal('total_pembayaran', 10, 2)->nullable();
            $table->string('lembar_keaslian')->nullable();
            $table->string('lembar_persetujuan_penguji')->nullable();
            $table->string('lembar_persetujuan_pembimbing')->nullable();
            $table->string('lembar_bimbingan')->nullable();
            $table->string('lembar_revisi')->nullable();
            $table->string('link_project')->nullable();
            $table->string('artikel')->nullable();
            $table->string('lampiran')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jilid_kps');
    }
};
