<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCapaianDanRealisasiKinerjaDanAnggaranTahunan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->string('realisasi')->nullable();
            $table->float('capaian')->nullable();
            $table->unsignedBigInteger('realisasi_anggaran')->default(0);
            $table->float('capaian_anggaran')->nullable();
        });

        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->string('realisasi')->nullable();
            $table->float('capaian')->nullable();
            $table->unsignedBigInteger('realisasi_anggaran')->default(0);
            $table->float('capaian_anggaran')->nullable();
        });

        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->float('capaian')->nullable();
            $table->unsignedBigInteger('realisasi_anggaran')->default(0);
            $table->float('capaian_anggaran')->nullable();
        });

        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->string('realisasi')->nullable();
            $table->float('capaian')->nullable();
            $table->unsignedBigInteger('realisasi_anggaran')->default(0);
            $table->float('capaian_anggaran')->nullable();
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
            $table->dropColumn(['realisasi', 'capaian', 'realisasi_anggaran', 'capaian_anggaran']);
        });

        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->dropColumn(['realisasi', 'capaian', 'realisasi_anggaran', 'capaian_anggaran']);
        });

        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn(['capaian', 'realisasi_anggaran', 'capaian_anggaran']);
        });

        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->dropColumn(['realisasi', 'capaian', 'realisasi_anggaran', 'capaian_anggaran']);
        });
    }
}
