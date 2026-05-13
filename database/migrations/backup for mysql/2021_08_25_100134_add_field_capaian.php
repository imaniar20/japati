<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCapaian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpjmd_visi_misi', function (Blueprint $table) {
            $table->float('capaian_baseline')->nullable();
            $table->float('capaian_1')->nullable();
            $table->float('capaian_2')->nullable();
            $table->float('capaian_3')->nullable();
            $table->float('capaian_4')->nullable();
            $table->float('capaian_5')->nullable();
        });

        Schema::table('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->float('capaian_baseline')->nullable();
            $table->float('capaian_1')->nullable();
            $table->float('capaian_2')->nullable();
            $table->float('capaian_3')->nullable();
            $table->float('capaian_4')->nullable();
            $table->float('capaian_5')->nullable();
        });

        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->float('capaian_baseline')->nullable();
            $table->float('capaian_1')->nullable();
            $table->float('capaian_2')->nullable();
            $table->float('capaian_3')->nullable();
            $table->float('capaian_4')->nullable();
            $table->float('capaian_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rpjmd_visi_misi', function (Blueprint $table) {
            $table->dropColumn(['capaian_baseline', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5']);
        });

        Schema::table('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->dropColumn(['capaian_baseline', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5']);
        });

        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->dropColumn(['capaian_baseline', 'capaian_1', 'capaian_2', 'capaian_3', 'capaian_4', 'capaian_5']);
        });
    }
}
