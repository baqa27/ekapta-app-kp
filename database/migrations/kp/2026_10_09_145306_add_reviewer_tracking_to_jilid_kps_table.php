<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jilid_kps', function (Blueprint $table) {
            // No foreign key: reviewer can be admin or prodi (different tables).
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
            $table->string('reviewer_type')->nullable()->after('reviewed_by');
            $table->timestamp('tanggal_review')->nullable()->after('reviewer_type');
        });
    }

    public function down(): void
    {
        Schema::table('jilid_kps', function (Blueprint $table) {
            $table->dropColumn(['reviewed_by', 'reviewer_type', 'tanggal_review']);
        });
    }
};
