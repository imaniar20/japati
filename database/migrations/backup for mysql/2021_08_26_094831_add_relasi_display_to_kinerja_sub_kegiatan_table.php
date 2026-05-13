<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelasiDisplayToKinerjaSubKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('rpjmd_sasaran_strategis_id')->index()->nullable();
            $table->unsignedBigInteger('kinerja_program_id')->index()->nullable();
            $table->unsignedBigInteger('kinerja_kegiatan_id')->index()->nullable();
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
            $table->dropIndex(['rpjmd_sasaran_strategis_id']);
            $table->dropIndex(['kinerja_program_id']);
            $table->dropIndex(['kinerja_kegiatan_id']);

            $table->dropColumn([
                'rpjmd_sasaran_strategis_id',
                'kinerja_program_id',
                'kinerja_kegiatan_id',
            ]);
        });
    }
}
