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
        $defaultValue = '{"1": 0, "2": 0, "3": 0, "4": 0}';

        Schema::table('sasaran_strategis_pd', function (Blueprint $table) use ($defaultValue) {
            $table->jsonb('target_baseline_triwulan')->default($defaultValue);
            $table->jsonb('target_1_triwulan')->default($defaultValue);
            $table->jsonb('target_2_triwulan')->default($defaultValue);
            $table->jsonb('target_3_triwulan')->default($defaultValue);
            $table->jsonb('target_4_triwulan')->default($defaultValue);
            $table->jsonb('target_5_triwulan')->default($defaultValue);

            $table->jsonb('realisasi_baseline_triwulan')->default($defaultValue);
            $table->jsonb('realisasi_1_triwulan')->default($defaultValue);
            $table->jsonb('realisasi_2_triwulan')->default($defaultValue);
            $table->jsonb('realisasi_3_triwulan')->default($defaultValue);
            $table->jsonb('realisasi_4_triwulan')->default($defaultValue);
            $table->jsonb('realisasi_5_triwulan')->default($defaultValue);

            $table->jsonb('capaian_baseline_triwulan')->default($defaultValue);
            $table->jsonb('capaian_1_triwulan')->default($defaultValue);
            $table->jsonb('capaian_2_triwulan')->default($defaultValue);
            $table->jsonb('capaian_3_triwulan')->default($defaultValue);
            $table->jsonb('capaian_4_triwulan')->default($defaultValue);
            $table->jsonb('capaian_5_triwulan')->default($defaultValue);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            $table->dropColumn([
                'target_baseline_triwulan',
                'target_1_triwulan',
                'target_2_triwulan',
                'target_3_triwulan',
                'target_4_triwulan',
                'target_5_triwulan',
                'realisasi_baseline_triwulan',
                'realisasi_1_triwulan',
                'realisasi_2_triwulan',
                'realisasi_3_triwulan',
                'realisasi_4_triwulan',
                'realisasi_5_triwulan',
                'capaian_baseline_triwulan',
                'capaian_1_triwulan',
                'capaian_2_triwulan',
                'capaian_3_triwulan',
                'capaian_4_triwulan',
                'capaian_5_triwulan',
            ]);
        });
    }
};
