<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKinerjaSubKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedBigInteger('kegiatan_id')->index();
            $table->unsignedBigInteger('sub_kegiatan_id')->index();
            $table->text('sasaran')->nullable();
            $table->text('indikator');
            $table->string('satuan');
            $table->float('target_tahunan');
            $table->json('target_bulanan');
            $table->float('realisasi_tahunan');
            $table->unsignedBigInteger('anggaran_tahunan');
            $table->json('anggaran_bulanan');
            $table->boolean('has_inovasi');
            $table->text('inovasi_uraian')->nullable();
            $table->text('inovasi_tujuan')->nullable();
            $table->json('inovasi_lampiran')->nullable();
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
        Schema::dropIfExists('kinerja_sub_kegiatan');
    }
}
