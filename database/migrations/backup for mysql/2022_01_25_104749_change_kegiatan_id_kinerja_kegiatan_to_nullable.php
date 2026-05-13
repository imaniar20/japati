<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKegiatanIdKinerjaKegiatanToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('kegiatan_id')->nullable()->change();
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
            $table->unsignedBigInteger('kegiatan_id')->nullable(false)->change();
        });
    }
}
