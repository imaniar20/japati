<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsDisplayMikroToKinerjaKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('satker_sasaran_strategis_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->dropIndex(['satker_sasaran_strategis_id']);

            $table->dropColumn(['satker_sasaran_strategis_id']);
        });
    }
}
