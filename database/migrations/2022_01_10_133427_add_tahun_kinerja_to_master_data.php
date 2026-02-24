<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahunKinerjaToMasterData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach (['program', 'kegiatan', 'sub_kegiatan'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedInteger('tahun_kinerja')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['program', 'kegiatan', 'sub_kegiatan'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropIndex(['tahun_kinerja']);
                $table->dropColumn('tahun_kinerja');
            });
        }
    }
}
