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
        Schema::create('kamus_indikator_fungsional_sasaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->foreignId('pengampu_id')->constrained('kamus_indikator_fungsional_pengampu');
            $table->text('sasaran');
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
        Schema::dropIfExists('kamus_indikator_fungsional_sasaran');
    }
};
