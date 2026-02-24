<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLKIPNarasiPDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lkip_narasi_pd', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('sasaran_strategis_id')->index();
            $table->unsignedBigInteger('sasaran_strategis_indikator_id')->index();
            $table->text('sasaran_strategis_pd');
            $table->text('iku_pd');
            $table->text('narasi_1')->nullable();
            $table->text('narasi_2')->nullable();
            $table->text('narasi_3')->nullable();
            $table->text('narasi_4')->nullable();
            $table->text('narasi_5')->nullable();
            $table->text('narasi_6')->nullable();
            $table->text('narasi_7')->nullable();
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
        Schema::dropIfExists('lkip_narasi_pd');
    }
}
