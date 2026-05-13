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
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->boolean('is_penggunaan_pdn')->default(false);
        });
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->boolean('is_penggunaan_pdn')->default(false);
        });
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->boolean('is_penggunaan_pdn')->default(false);
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->boolean('is_penggunaan_pdn')->default(false);
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->boolean('is_penggunaan_pdn')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->dropColumn('is_penggunaan_pdn');
        });
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->dropColumn('is_penggunaan_pdn');
        });
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->dropColumn('is_penggunaan_pdn');
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->dropColumn('is_penggunaan_pdn');
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn('is_penggunaan_pdn');
        });
    }
};
