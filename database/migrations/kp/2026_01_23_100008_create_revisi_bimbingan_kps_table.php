<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_bimbingan_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbingan_id')->constrained('bimbingan_kps')->onDelete('cascade');
            $table->text('catatan');
            $table->string('lampiran')->nullable();
            $table->string('lampiran_revisi')->nullable();
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->onDelete('cascade');
            $table->string('reviewer_type')->nullable();
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_bimbingan_kps');
    }
};
