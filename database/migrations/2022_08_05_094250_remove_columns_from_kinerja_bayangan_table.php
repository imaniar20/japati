<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kinerja_bayangan', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['sasaran_strategis_rpjmd_id']);
            $table->dropColumn(['parent_id', 'sasaran_strategis_rpjmd_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_bayangan', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('sasaran_strategis_rpjmd_id')->nullable()->index();
        });
    }
};
