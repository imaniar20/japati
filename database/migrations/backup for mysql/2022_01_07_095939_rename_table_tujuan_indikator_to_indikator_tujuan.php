<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableTujuanIndikatorToIndikatorTujuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('tujuan_indikator', 'indikator_tujuan');
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->renameColumn('tujuan_indikator_id', 'indikator_tujuan_id');
        });
        Schema::table('visi_misi_rpjmd', function (Blueprint $table) {
            $table->renameColumn('tujuan_indikator_id', 'indikator_tujuan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('indikator_tujuan', 'tujuan_indikator');
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->renameColumn('indikator_tujuan_id', 'tujuan_indikator_id');
        });
        Schema::table('visi_misi_rpjmd', function (Blueprint $table) {
            $table->renameColumn('indikator_tujuan_id', 'tujuan_indikator_id');
        });
    }
}
