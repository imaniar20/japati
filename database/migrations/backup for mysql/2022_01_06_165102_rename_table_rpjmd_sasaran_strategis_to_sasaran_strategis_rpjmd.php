<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableRpjmdSasaranStrategisToSasaranStrategisRpjmd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('rpjmd_sasaran_strategis', 'sasaran_strategis_rpjmd');
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->renameColumn('rpjmd_sasaran_strategis_id', 'sasaran_strategis_rpjmd_id');
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->renameColumn('rpjmd_sasaran_strategis_id', 'sasaran_strategis_rpjmd_id');
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->renameColumn('rpjmd_sasaran_strategis_id', 'sasaran_strategis_rpjmd_id');
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->renameColumn('rpjmd_sasaran_strategis_id', 'sasaran_strategis_rpjmd_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('sasaran_strategis_rpjmd', 'rpjmd_sasaran_strategis');
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_rpjmd_id', 'rpjmd_sasaran_strategis_id');
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_rpjmd_id', 'rpjmd_sasaran_strategis_id');
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_rpjmd_id', 'rpjmd_sasaran_strategis_id');
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_rpjmd_id', 'rpjmd_sasaran_strategis_id');
        });
    }
}
