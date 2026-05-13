<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPeningkatanDanPenghargaanToRpjmdSasaranStrategisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->string('peningkatan_realisasi')->nullable();
            $table->float('capaian_terhadap_target_akhir')->nullable();
            $table->string('penghargaan')->nullable();
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
            $table->dropColumn(['peningkatan_realisasi', 'capaian_terhadap_target_akhir', 'penghargaan']);
        });
    }
}
