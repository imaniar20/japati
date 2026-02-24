<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'visi',
        'misi',
        'tujuan',
        'indikator_tujuan',
        'sasaran_strategis',
        'indikator_sasaran_strategis',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                $table->unsignedInteger('tahun_mulai')->default(0);

                if ($t != 'visi') {
                    $table->unique(['tahun_mulai', 'nomor']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                if ($t != 'visi') {
                    $table->dropUnique(['tahun_mulai', 'nomor']);
                }

                $table->dropColumn('tahun_mulai');
            });
        }
    }
};
