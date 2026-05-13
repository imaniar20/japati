<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('sub_kegiatan', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('visi_misi_rpjmd', function (Blueprint $table) {
            $table->index(['tahun_mulai', 'satuan_kerja_id']);
        });
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->index(['tahun_mulai', 'satuan_kerja_id']);
        });
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->index(['tahun_mulai', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->index(['tahun_kinerja', 'satuan_kerja_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('sub_kegiatan', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('visi_misi_rpjmd', function (Blueprint $table) {
            $table->dropIndex(['tahun_mulai', 'satuan_kerja_id']);
        });
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->dropIndex(['tahun_mulai', 'satuan_kerja_id']);
        });
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->dropIndex(['tahun_mulai', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->dropIndex(['tahun_kinerja', 'satuan_kerja_id']);
        });
    }
};
