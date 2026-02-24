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
        Schema::create('lke_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_kinerja')->nullable();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->string('rekomendasi')->nullable();
            $table->string('tindak_lanjut')->nullable();
            $table->string('link_bukti_dukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_rekomendasi');
    }
};
