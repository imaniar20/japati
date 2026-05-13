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
        Schema::create('lke_penilaian_komponen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('lke_penilaian');
            $table->foreignId('komponen_id')->constrained('lke_komponen');
            $table->float('nilai')->nullable();
            $table->timestamps();

            $table->unique(['penilaian_id', 'komponen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lke_penilaian_komponen');
    }
};
