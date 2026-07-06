<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToReviewUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_ujians', function (Blueprint $table) {
            if (!Schema::hasColumn('review_ujians', 'tanggal_acc_manual')) {
                $table->date('tanggal_acc_manual')->nullable();
            }
            if (!Schema::hasColumn('review_ujians', 'lampiran_lembar_revisi')) {
                $table->string('lampiran_lembar_revisi')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_ujians', function (Blueprint $table) {
            if (Schema::hasColumn('review_ujians', 'lampiran_lembar_revisi')) {
                $table->dropColumn('lampiran_lembar_revisi');
            }
            if (Schema::hasColumn('review_ujians', 'tanggal_acc_manual')) {
                $table->dropColumn('tanggal_acc_manual');
            }
        });
    }
}
