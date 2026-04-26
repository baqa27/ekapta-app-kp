<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_kps')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('no_wa')->nullable();
            $table->string('lampiran_1')->nullable();
            $table->string('lampiran_2')->nullable();
            $table->string('lampiran_3')->nullable();
            $table->string('lampiran_4')->nullable();
            $table->decimal('jumlah_bayar', 10, 2)->nullable();
            $table->string('metode_bayar')->nullable();
            $table->string('nomor_pembayaran')->nullable();
            $table->string('lampiran_proposal')->nullable();
            $table->string('link_akses_produk')->nullable();
            $table->string('dokumen_penilaian')->nullable();
            $table->integer('is_valid')->default(0);
            $table->boolean('is_lulus')->default(false);
            $table->timestamp('tanggal_acc')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamp('tanggal_ujian')->nullable();
            $table->string('tempat_ujian')->nullable();
            $table->string('judul_laporan')->nullable();
            $table->string('file_laporan')->nullable();
            $table->string('file_pengesahan')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->string('file_laporan_revisi')->nullable();
            $table->string('bukti_perbaikan')->nullable();
            $table->decimal('nilai_instansi', 5, 2)->nullable();
            $table->decimal('nilai_pembimbing', 5, 2)->nullable();
            $table->decimal('nilai_penguji', 5, 2)->nullable();
            $table->string('file_nilai_instansi')->nullable();
            $table->decimal('nilai_seminar', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->string('status_nilai')->nullable();
            $table->string('status_seminar')->default('menunggu_verifikasi');
            $table->text('catatan_himpunan')->nullable();
            $table->text('catatan_penguji')->nullable();
            $table->foreignId('sesi_seminar_id')->nullable()->constrained('sesi_seminar_kps')->onDelete('set null');
            $table->integer('urutan_presentasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_kps');
    }
};
