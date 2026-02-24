<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRpjmdSasaranStrategisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedBigInteger('sasaran_strategis_id')->index();
            $table->unsignedBigInteger('sasaran_strategis_indikator_id')->index();
            $table->string('satuan');
            $table->unsignedInteger('tahun_mulai');
            $table->float('target_baseline');
            $table->float('target_1');
            $table->float('target_2');
            $table->float('target_3');
            $table->float('target_4');
            $table->float('target_5');
            $table->float('realisasi_baseline')->nullable();
            $table->float('realisasi_1')->nullable();
            $table->float('realisasi_2')->nullable();
            $table->float('realisasi_3')->nullable();
            $table->float('realisasi_4')->nullable();
            $table->float('realisasi_5')->nullable();
            $table->float('rata_nasional')->nullable();
            $table->unsignedInteger('peringkat_nasional')->nullable();
            $table->text('strategi')->nullable();
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
        Schema::dropIfExists('rpjmd_sasaran_strategis');
    }
}
