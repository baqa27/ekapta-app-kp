<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi_kp')->nullable();
            $table->string('alamat_instansi')->nullable();
            $table->text('files_pendukung')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('status')->default('review');
            $table->timestamp('tanggal_acc')->nullable();
            $table->integer('jumlah_tolak')->default(0);
            $table->string('file_kerangka_pikir')->nullable();
            $table->string('status_kerangka_pikir')->nullable();
            $table->timestamp('tanggal_acc_kerangka')->nullable();
            $table->string('file_bukti_penerimaan_instansi')->nullable();
            $table->string('file_persetujuan_kerangka')->nullable();
            $table->string('file_lembar_persetujuan_pembimbing')->nullable();
            $table->foreignId('calon_pembimbing_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kps');
    }
};
