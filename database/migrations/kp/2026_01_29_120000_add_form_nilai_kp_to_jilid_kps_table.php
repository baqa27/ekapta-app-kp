<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jilid_kps', function (Blueprint $table) {
            $table->string('form_nilai_kp')->nullable()->after('file_project');
        });
    }

    public function down(): void
    {
        Schema::table('jilid_kps', function (Blueprint $table) {
            $table->dropColumn('form_nilai_kp');
        });
    }
};
