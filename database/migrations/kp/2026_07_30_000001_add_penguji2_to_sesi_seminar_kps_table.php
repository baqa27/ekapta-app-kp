<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi: Tambah kolom dosen_penguji_id_2 (opsional) ke tabel sesi_seminar_kps
 * Penguji 2 bersifat opsional — tidak wajib ada, tidak wajib input nilai semua mahasiswa.
 */
class AddPenguji2ToSesiSeminarKpsTable extends Migration
{
    public function up()
    {
        Schema::table('sesi_seminar_kps', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_penguji_id_2')->nullable()->after('dosen_penguji_id');
            $table->foreign('dosen_penguji_id_2')->references('id')->on('dosens')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('sesi_seminar_kps', function (Blueprint $table) {
            $table->dropForeign(['dosen_penguji_id_2']);
            $table->dropColumn('dosen_penguji_id_2');
        });
    }
}
