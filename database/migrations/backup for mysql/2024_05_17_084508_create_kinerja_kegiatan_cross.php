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
        Schema::create('kinerja_kegiatan_cross', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kinerja_program_id')->constrained('kinerja_program')->cascadeOnDelete();
            $table->foreignId('kinerja_kegiatan_id')->constrained('kinerja_kegiatan')->cascadeOnDelete();

            $table->unique(['kinerja_program_id', 'kinerja_kegiatan_id'], 'kp_kk_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kinerja_kegiatan_cross');
    }
};
