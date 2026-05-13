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
        Schema::create('perubahan_jumlah_output_v2', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->integer('tw1')->default(0);
            $table->integer('tw2')->default(0);
            $table->integer('tw3')->default(0);
            $table->integer('tw4')->default(0);
            $table->timestamps();

            $table->unique(['satuan_kerja_id', 'tahun_kinerja']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_jumlah_output_v2');
    }
};
