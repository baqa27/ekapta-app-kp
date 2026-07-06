<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_kps')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('hp')->nullable();
            $table->integer('semester')->nullable();
            $table->string('nomor_pembayaran')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->decimal('biaya', 10, 2)->nullable();
            $table->string('jenis_mahasiswa')->nullable();
            $table->string('lampiran_1')->nullable();
            $table->string('lampiran_2')->nullable();
            $table->string('lampiran_3')->nullable();
            $table->string('lampiran_4')->nullable();
            $table->string('lampiran_5')->nullable();
            $table->string('lampiran_6')->nullable();
            $table->string('lampiran_7')->nullable();
            $table->string('lampiran_8')->nullable();
            $table->string('lampiran_acc')->nullable();
            $table->timestamp('tanggal_acc')->nullable();
            $table->string('status')->default('review');
            $table->string('status_pembayaran')->default('belum_bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('tanggal_verifikasi_bayar')->nullable();
            $table->string('nomor_surat_tugas')->nullable();
            $table->date('tanggal_surat_tugas')->nullable();
            $table->string('file_surat_tugas')->nullable();
            $table->string('file_lembar_bimbingan')->nullable();
            $table->integer('jumlah_perpanjangan')->default(0);
            $table->date('tanggal_perpanjangan_terakhir')->nullable();
            $table->string('sertifikat_peserta_1')->nullable();
            $table->string('sertifikat_peserta_2')->nullable();
            $table->text('dokumen_pendukung')->nullable();
            $table->text('dokumen_pendukung_kp')->nullable();
            $table->date('masa_berlaku_surat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_kps');
    }
};
