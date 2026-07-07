<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArtikelFieldsToJilidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jilids', function (Blueprint $table) {
            if (!Schema::hasColumn('jilids', 'file_artikel')) {
                $table->string('file_artikel')->nullable()->after('artikel');
            }
            if (!Schema::hasColumn('jilids', 'file_loa')) {
                $table->string('file_loa')->nullable()->after('file_artikel');
            }
            if (!Schema::hasColumn('jilids', 'status_artikel')) {
                $table->string('status_artikel')->nullable()->after('file_loa');
            }
            if (!Schema::hasColumn('jilids', 'link_artikel')) {
                $table->string('link_artikel')->nullable()->after('status_artikel');
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
        Schema::table('jilids', function (Blueprint $table) {
            $table->dropColumn(['file_artikel', 'file_loa', 'status_artikel', 'link_artikel']);
        });
    }
}
