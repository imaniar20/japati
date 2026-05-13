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
            $table->json('validasi_pengampu')->default('{ "status": null, "catatan": null }');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->dropColumn('validasi_pengampu');
        });
    }
};
