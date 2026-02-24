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
        Schema::create('lke_catatan_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->integer('satuan_kerja_id');
            $table->integer('tahun_kinerja');
            $table->foreignId('user_id')->constrained('users');
            $table->json('catatan')->nullable();
            $table->json('rekomendasi')->nullable();
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
        Schema::dropIfExists('lke_catatan_rekomendasi');
    }
};
