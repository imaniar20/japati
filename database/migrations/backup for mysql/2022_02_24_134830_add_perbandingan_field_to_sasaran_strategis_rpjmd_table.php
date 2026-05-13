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
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            // Perbandingan realisasi dari tahun lalu
            $table->string('perbandingan_realisasi_tahun_1')->nullable(); // 2019 - baseline
            $table->string('perbandingan_realisasi_tahun_2')->nullable(); // 2020 - 2019
            $table->string('perbandingan_realisasi_tahun_3')->nullable(); // 2021 - 2020
            $table->string('perbandingan_realisasi_tahun_4')->nullable(); // 2022 - 2021
            $table->string('perbandingan_realisasi_tahun_5')->nullable(); // 2023 - 2022

            // Perbandingan realisasi kinerja tahun ini dengan target RPJMD
            $table->string('perbandingan_realisasi_target_1')->nullable();
            $table->string('perbandingan_realisasi_target_2')->nullable();
            $table->string('perbandingan_realisasi_target_3')->nullable();
            $table->string('perbandingan_realisasi_target_4')->nullable();
            $table->string('perbandingan_realisasi_target_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->dropColumn([
                'perbandingan_realisasi_tahun_1',
                'perbandingan_realisasi_tahun_2',
                'perbandingan_realisasi_tahun_3',
                'perbandingan_realisasi_tahun_4',
                'perbandingan_realisasi_tahun_5',
                'perbandingan_realisasi_target_1',
                'perbandingan_realisasi_target_2',
                'perbandingan_realisasi_target_3',
                'perbandingan_realisasi_target_4',
                'perbandingan_realisasi_target_5',
            ]);
        });
    }
};
