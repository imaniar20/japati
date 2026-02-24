<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKinerjaKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinerja_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedBigInteger('program_id')->index();
            $table->text('sasaran_program');
            $table->unsignedBigInteger('kegiatan_id')->index();
            $table->text('sasaran')->nullable();
            $table->text('indikator');
            $table->string('satuan');
            $table->string('target');
            $table->jsonb('target_bulanan');
            $table->jsonb('realisasi_bulanan');
            $table->unsignedBigInteger('anggaran');
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
        Schema::dropIfExists('kinerja_kegiatan');
    }
}
