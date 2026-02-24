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
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->text('indikator_kemendagri')->nullable();
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
            $table->dropColumn('indikator_kemendagri');
        });
    }
};
