<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRealisasiKinerjaDanRealisasiAnggaranKinerjaSubKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaultKinerja = '{"apr": null, "aug": null, "dec": null, "feb": null, "jan": null, "jul": null, "jun": null, "mar": null, "may": null, "nov": null, "oct": null, "sep": null}';
        $defaultAnggaran = '{"apr": 0, "aug": 0, "dec": 0, "feb": 0, "jan": 0, "jul": 0, "jun": 0, "mar": 0, "may": 0, "nov": 0, "oct": 0, "sep": 0}';

        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->json('realisasi_bulanan');
            $table->json('realisasi_anggaran_bulanan')->nullable();;
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
            $table->dropColumn(['realisasi_bulanan', 'realisasi_anggaran_bulanan']);
        });
    }
}
