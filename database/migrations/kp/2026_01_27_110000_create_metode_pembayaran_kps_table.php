<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metode_pembayaran_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('himpunan_id')->constrained('himpunan_kps')->onDelete('cascade');
            $table->enum('tipe', ['bank', 'ewallet']); // Tipe metode pembayaran
            $table->string('nama'); // Nama bank/ewallet (BNI, DANA, OVO, dll)
            $table->string('nomor'); // Nomor rekening/ewallet
            $table->string('nama_pemilik'); // Atas nama
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // Untuk sorting
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran_kps');
    }
};
