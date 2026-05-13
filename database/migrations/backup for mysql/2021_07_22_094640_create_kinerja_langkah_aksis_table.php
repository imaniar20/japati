<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKinerjaLangkahAksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedBigInteger('sub_kegiatan_id')->index();
            $table->text('langkah_aksi');
            $table->text('sasaran')->nullable();
            $table->text('indikator');
            $table->string('satuan');
            $table->float('target_tahunan');
            $table->json('target_bulanan');
            $table->unsignedBigInteger('anggaran_tahunan');
            $table->json('anggaran_bulanan');
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
        Schema::dropIfExists('kinerja_langkah_aksi');
    }
}
