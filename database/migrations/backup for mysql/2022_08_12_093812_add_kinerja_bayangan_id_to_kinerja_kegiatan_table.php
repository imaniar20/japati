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
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('kinerja_bayangan_id')->nullable()->index();
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
            $table->dropIndex(['kinerja_bayangan_id']);
            $table->dropColumn('kinerja_bayangan_id');
        });
    }
};
