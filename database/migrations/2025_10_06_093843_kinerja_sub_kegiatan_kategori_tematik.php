<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kinerja_sub_kegiatan_kategori_tematik', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_tematik_id')->index();
            $table->unsignedBigInteger('kinerja_sub_kegiatan_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kinerja_sub_kegiatan_kategori_tematik');
    }
};
