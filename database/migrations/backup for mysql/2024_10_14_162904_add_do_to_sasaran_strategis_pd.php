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
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->text('do_narasi')->nullable();
            $table->text('do_rumus')->nullable();
            $table->text('do_keterangan')->nullable();
            $table->text('do_sumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->dropColumn(['do_narasi', 'do_rumus', 'do_keterangan', 'do_sumber']);
        });
    }
};
