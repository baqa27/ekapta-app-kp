<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_jilid_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jilid_id')->constrained('jilid_kps')->onDelete('cascade');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_jilid_kps');
    }
};
