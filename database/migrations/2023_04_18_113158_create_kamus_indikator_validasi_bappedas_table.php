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
        Schema::create('kamus_indikator_validasi_bappeda', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->boolean('is_validasi');
            $table->timestamps();

            $table->unique(['satuan_kerja_id', 'tahun_kinerja']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_indikator_validasi_bappeda');
    }
};
