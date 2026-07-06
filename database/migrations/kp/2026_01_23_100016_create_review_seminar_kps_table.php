<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_seminar_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_id')->constrained('seminar_kps')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->string('dosen_status')->nullable(); // pembimbing/penguji
            $table->string('status')->default('review'); // review/revisi/diterima
            $table->string('role')->nullable();
            $table->integer('nilai_1')->nullable();
            $table->integer('nilai_2')->nullable();
            $table->integer('nilai_3')->nullable();
            $table->integer('nilai_4')->nullable();
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->decimal('nilai_pembimbing', 5, 2)->nullable();
            $table->decimal('nilai_penguji', 5, 2)->nullable();
            $table->decimal('nilai_instansi', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->boolean('is_nilai_manual')->default(false);
            $table->string('status_hasil')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_penguji')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('lampiran_lembar_revisi')->nullable();
            $table->timestamp('tanggal_acc')->nullable();
            $table->timestamp('tanggal_acc_manual')->nullable();
            $table->string('token')->nullable();
            $table->boolean('is_dinilai')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_seminar_kps');
    }
};
