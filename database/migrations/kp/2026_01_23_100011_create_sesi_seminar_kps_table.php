<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_seminar_kps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi');
            $table->date('tanggal_seminar')->nullable();
            $table->date('tanggal')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('tempat')->nullable();
            $table->integer('kuota')->default(0);
            $table->integer('jumlah_mahasiswa')->default(0);
            $table->integer('terisi')->default(0);
            $table->foreignId('himpunan_id')->nullable()->constrained('himpunan_kps')->onDelete('set null');
            $table->foreignId('dosen_penguji_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->string('status')->default('aktif');
            $table->boolean('pendaftaran_dibuka')->default(true);
            $table->text('catatan')->nullable();
            $table->text('catatan_teknis')->nullable();
            $table->string('token_penilaian')->nullable();
            $table->boolean('is_token_used')->default(false);
            $table->timestamp('token_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_seminar_kps');
    }
};
