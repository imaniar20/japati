<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_jenjang_kinerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedBigInteger('sasaran_strategis_pd_id')->index();
            $table->float('nilai');
            $table->text('keterangan')->nullable();
            $table->text('eviden')->nullable();
            $table->timestamps();

            $table->unique(
                [
                    'satuan_kerja_id',
                    'tahun_kinerja',
                    'sasaran_strategis_pd_id'
                ],
                'satker_tahun_sspd_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_jenjang_kinerja');
    }
};
