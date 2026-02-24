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
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->unsignedBigInteger('penyebab_kegagalan_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->dropIndex(['penyebab_kegagalan_id']);
            $table->dropColumn('penyebab_kegagalan_id');
        });
    }
};
