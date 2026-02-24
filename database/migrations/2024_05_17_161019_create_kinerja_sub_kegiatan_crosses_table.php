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
        Schema::create('kinerja_sub_kegiatan_cross', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kinerja_kegiatan_id')->constrained('kinerja_kegiatan')->cascadeOnDelete();
            $table->foreignId('kinerja_sub_kegiatan_id')->constrained('kinerja_sub_kegiatan')->cascadeOnDelete();

            $table->unique(['kinerja_kegiatan_id', 'kinerja_sub_kegiatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kinerja_sub_kegiatan_cross');
    }
};
