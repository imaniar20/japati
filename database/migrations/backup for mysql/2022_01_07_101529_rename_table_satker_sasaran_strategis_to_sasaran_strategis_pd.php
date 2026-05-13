<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableSatkerSasaranStrategisToSasaranStrategisPd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('satker_sasaran_strategis', 'sasaran_strategis_pd');
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->renameColumn('satker_sasaran_strategis_id', 'sasaran_strategis_pd_id');
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->renameColumn('satker_sasaran_strategis_id', 'sasaran_strategis_pd_id');
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->renameColumn('satker_sasaran_strategis_id', 'sasaran_strategis_pd_id');
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->renameColumn('satker_sasaran_strategis_id', 'sasaran_strategis_pd_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('sasaran_strategis_pd', 'satker_sasaran_strategis');
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_pd_id', 'satker_sasaran_strategis_id');
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_pd_id', 'satker_sasaran_strategis_id');
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_pd_id', 'satker_sasaran_strategis_id');
        });
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_pd_id', 'satker_sasaran_strategis_id');
        });
    }
}
