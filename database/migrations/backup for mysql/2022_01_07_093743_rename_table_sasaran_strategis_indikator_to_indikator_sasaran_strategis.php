<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableSasaranStrategisIndikatorToIndikatorSasaranStrategis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('sasaran_strategis_indikator', 'indikator_sasaran_strategis');
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_indikator_id', 'indikator_sasaran_strategis_id');
        });
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_indikator_id', 'indikator_sasaran_strategis_id');
        });
        Schema::table('lkip_narasi_pemda', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_indikator_id', 'indikator_sasaran_strategis_id');
        });
        Schema::table('lkip_narasi_pd', function (Blueprint $table) {
            $table->renameColumn('sasaran_strategis_indikator_id', 'indikator_sasaran_strategis_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('indikator_sasaran_strategis', 'sasaran_strategis_indikator');
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->renameColumn('indikator_sasaran_strategis_id', 'sasaran_strategis_indikator_id');
        });
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->renameColumn('indikator_sasaran_strategis_id', 'sasaran_strategis_indikator_id');
        });
        Schema::table('lkip_narasi_pemda', function (Blueprint $table) {
            $table->renameColumn('indikator_sasaran_strategis_id', 'sasaran_strategis_indikator_id');
        });
        Schema::table('lkip_narasi_pd', function (Blueprint $table) {
            $table->renameColumn('indikator_sasaran_strategis_id', 'sasaran_strategis_indikator_id');
        });
    }
}
