<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelasiCascading extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->unsignedBigInteger('rpjmd_sasaran_strategis_id')->index()->nullable();
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
            $table->dropIndex(['rpjmd_sasaran_strategis_id']);

            $table->dropColumn([
                'rpjmd_sasaran_strategis_id',
            ]);
        });
    }
}
