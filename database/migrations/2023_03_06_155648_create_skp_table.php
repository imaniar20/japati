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
        Schema::create('skp', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_mulai')->index();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->string('pengampu')->nullable();
            $table->string('v_struktur_organisasi_id')->nullable()->index();
            $table->unsignedBigInteger('tim_kerja_id')->nullable()->index();
            $table->string('model_class');
            $table->unsignedBigInteger('model_id');
            $table->text('sasaran');
            $table->text('indikator');
            $table->string('target');
            $table->text('satuan');
            $table->timestamps();

            $table->unique(['tahun_kinerja', 'model_class', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skp');
    }
};
