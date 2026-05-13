<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayRpjmdSasaranStrategis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->unsignedBigInteger('misi_id')->index()->nullable();
            $table->unsignedBigInteger('tujuan_id')->index()->nullable();
            $table->unsignedBigInteger('tujuan_indikator_id')->index()->nullable();
            $table->unsignedBigInteger('target_rpjmd_visi_misi_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->dropIndex(['misi_id']);
            $table->dropIndex(['tujuan_id']);
            $table->dropIndex(['tujuan_indikator_id']);
            $table->dropIndex(['target_rpjmd_visi_misi_id']);

            $table->dropColumn([
                'misi_id',
                'tujuan_id',
                'tujuan_indikator_id',
                'target_rpjmd_visi_misi_id',
            ]);
        });
    }
}
