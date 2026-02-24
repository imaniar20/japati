<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnTargetDanAnggaranTahunan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->renameColumn('target_tahunan', 'target');
            $table->renameColumn('realisasi_tahunan', 'realisasi');
            $table->renameColumn('anggaran_tahunan', 'anggaran');
        });

        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->renameColumn('target_tahunan', 'target');
            $table->renameColumn('anggaran_tahunan', 'anggaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->renameColumn('target', 'target_tahunan');
            $table->renameColumn('realisasi', 'realisasi_tahunan');
            $table->renameColumn('anggaran', 'anggaran_tahunan');
        });

        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->renameColumn('target', 'target_tahunan');
            $table->renameColumn('anggaran', 'anggaran_tahunan');
        });
    }
}
