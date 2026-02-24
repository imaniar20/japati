<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPengampuKinerjaSubKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->string('pengampu')->nullable();
            $table->unsignedBigInteger('tim_kerja_id')->index()->nullable();
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
            $table->dropColumn('pengampu');
            $table->dropIndex(['tim_kerja_id']);
            $table->dropColumn('tim_kerja_id');
        });
    }
}
