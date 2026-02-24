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
        Schema::table('lke_rekomendasi', function (Blueprint $table) {
            $table->string('target')->nullable();
            $table->string('waktu')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('status')->nullable();
            $table->string('link_eviden')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lke_rekomendasi', function (Blueprint $table) {
            $table->dropColumn(['target', 'waktu', 'penanggung_jawab', 'status', 'link_eviden']);
        });
    }
};
