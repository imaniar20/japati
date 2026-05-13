<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahunKinerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->unsignedInteger('tahun_kinerja')->nullable();
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->unsignedInteger('tahun_kinerja')->nullable();
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->unsignedInteger('tahun_kinerja')->nullable();
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->unsignedInteger('tahun_kinerja')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->dropColumn(['tahun_kinerja']);
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->dropColumn(['tahun_kinerja']);
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn(['tahun_kinerja']);
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->dropColumn(['tahun_kinerja']);
        });
    }
}
