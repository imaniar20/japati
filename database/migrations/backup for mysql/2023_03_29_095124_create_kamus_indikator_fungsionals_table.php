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
        Schema::create('kamus_indikator_fungsional', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('tahun_mulai')->index();
            $table->unsignedInteger('tahun_kinerja')->index();

            $table->unsignedBigInteger('satuan_kerja_id')->index();

            $table->string('model_class');

            $table->unsignedBigInteger('model_id');

            $table->string('pengampu')->nullable();

            $table->unsignedInteger('jabatan_id')
                ->nullable()
                ->index();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique(
                ['tahun_kinerja', 'model_class', 'model_id'],
                'kif_tahun_model_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_indikator_fungsional');
    }
};
