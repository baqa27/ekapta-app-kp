<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_review_seminar_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_seminar_id')->constrained('review_seminar_kps')->onDelete('cascade');
            $table->text('catatan');
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_review_seminar_kps');
    }
};
