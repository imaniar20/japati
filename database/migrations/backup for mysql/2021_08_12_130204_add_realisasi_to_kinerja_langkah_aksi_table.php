<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRealisasiToKinerjaLangkahAksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->json('realisasi_bulanan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->dropColumn('realisasi_bulanan');
        });
    }
}
