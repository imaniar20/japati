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
        Schema::create('kinerja_program_cross', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sasaran_strategis_pd_id')->constrained('sasaran_strategis_pd')->cascadeOnDelete();
            $table->foreignId('kinerja_program_id')->constrained('kinerja_program')->cascadeOnDelete();

            $table->unique(['sasaran_strategis_pd_id', 'kinerja_program_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kinerja_program_cross');
    }
};
