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
        Schema::create('sasaran_strategis_pd_cross', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sasaran_strategis_rpjmd_id')->constrained('sasaran_strategis_rpjmd')->cascadeOnDelete();
            $table->foreignId('sasaran_strategis_pd_id')->constrained('sasaran_strategis_pd')->cascadeOnDelete();

            $table->unique(['sasaran_strategis_rpjmd_id', 'sasaran_strategis_pd_id'], 'ssr_rpjmd_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sasaran_strategis_pd_cross');
    }
};
