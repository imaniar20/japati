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
        Schema::create('perubahan_jumlah_output', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedInteger('awal');
            $table->unsignedInteger('akhir');
            $table->unsignedInteger('perubahan');
            $table->timestamps();

            $table->unique(['satuan_kerja_id', 'tahun_kinerja']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_jumlah_output');
    }
};
