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
            $table->float('rata_internasional')->nullable();
            $table->integer('peringkat_internasional')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->dropColumn('rata_internasional');
            $table->dropColumn('peringkat_internasional');
        });
    }
};
