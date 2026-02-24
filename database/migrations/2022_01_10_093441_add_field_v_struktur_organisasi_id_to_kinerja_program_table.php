<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldVStrukturOrganisasiIdToKinerjaProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->string('v_struktur_organisasi_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->dropIndex(['v_struktur_organisasi_id']);
            $table->dropColumn('v_struktur_organisasi_id');
        });
    }
}
