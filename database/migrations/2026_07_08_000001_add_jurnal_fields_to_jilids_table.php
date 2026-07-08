<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJurnalFieldsToJilidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jilids', function (Blueprint $table) {
            if (!Schema::hasColumn('jilids', 'nama_jurnal')) {
                $table->string('nama_jurnal')->nullable()->after('link_artikel');
            }
            if (!Schema::hasColumn('jilids', 'kategori_jurnal')) {
                $table->string('kategori_jurnal')->nullable()->after('nama_jurnal');
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
            $table->dropColumn(['nama_jurnal', 'kategori_jurnal']);
        });
    }
}
