<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbingan_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('bagian_id')->constrained('bagian_kps')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('status')->default('review');
            $table->timestamp('tanggal_bimbingan')->nullable(); // Ubah dari date ke timestamp agar bisa simpan jam
            $table->timestamp('tanggal_acc')->nullable();
            $table->string('pembimbing')->nullable();
            $table->string('bukti_bimbingan_offline')->nullable();
            $table->string('tipe')->default('online');
            $table->string('status_offline')->nullable();
            $table->string('lampiran_acc')->nullable();
            $table->timestamp('tanggal_manual_acc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingan_kps');
    }
};
