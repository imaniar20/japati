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
        Schema::create('kinerja_bayangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('sasaran_strategis_rpjmd_id')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->string('sasaran');
            $table->string('indikator');
            $table->string('satuan');
            $table->unsignedInteger('tahun_mulai');
            $table->string('target_baseline');
            $table->string('target_1');
            $table->string('target_2');
            $table->string('target_3');
            $table->string('target_4');
            $table->string('target_5');
            $table->string('realisasi_baseline')->nullable();
            $table->string('realisasi_1')->nullable();
            $table->string('realisasi_2')->nullable();
            $table->string('realisasi_3')->nullable();
            $table->string('realisasi_4')->nullable();
            $table->string('realisasi_5')->nullable();
            $table->float('capaian_baseline')->nullable();
            $table->float('capaian_1')->nullable();
            $table->float('capaian_2')->nullable();
            $table->float('capaian_3')->nullable();
            $table->float('capaian_4')->nullable();
            $table->float('capaian_5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kinerja_bayangan');
    }
};
