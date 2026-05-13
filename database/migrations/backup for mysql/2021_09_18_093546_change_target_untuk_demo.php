<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTargetUntukDemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Artisan::call('db:seed', [
        //     '--class' => 'ConvertTargetToFloat'
        // ]);

        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {

            $table->double('target_baseline')->nullable()->change();
            $table->double('target_1')->nullable()->change();
            $table->double('target_2')->nullable()->change();
            $table->double('target_3')->nullable()->change();
            $table->double('target_4')->nullable()->change();
            $table->double('target_5')->nullable()->change();

            $table->double('realisasi_baseline')->nullable()->change();
            $table->double('realisasi_1')->nullable()->change();
            $table->double('realisasi_2')->nullable()->change();
            $table->double('realisasi_3')->nullable()->change();
            $table->double('realisasi_4')->nullable()->change();
            $table->double('realisasi_5')->nullable()->change();
        });

        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->double('target')->change();
            $table->double('realisasi')->nullable()->change();
        });

        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->double('target')->change();
            $table->double('realisasi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->string('target_baseline')->change();
            $table->string('target_1')->change();
            $table->string('target_2')->change();
            $table->string('target_3')->change();
            $table->string('target_4')->change();
            $table->string('target_5')->change();
            $table->string('realisasi_baseline')->change();
            $table->string('realisasi_1')->change();
            $table->string('realisasi_2')->change();
            $table->string('realisasi_3')->change();
            $table->string('realisasi_4')->change();
            $table->string('realisasi_5')->change();
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->string('target')->change();
            $table->string('realisasi')->change();
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->string('target')->change();
            $table->string('realisasi')->change();
        });
    }
}
