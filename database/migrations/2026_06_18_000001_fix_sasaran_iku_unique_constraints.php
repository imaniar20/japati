<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->fixUniqueConstraint('sasaran_strategis', [
            'sasaran_strategis_nomor_unique',
            'sasaran_strategis_tahun_mulai_nomor_unique',
        ]);

        $this->fixUniqueConstraint('indikator_sasaran_strategis', [
            'sasaran_strategis_indikator_nomor_unique',
            'indikator_sasaran_strategis_nomor_unique',
            'indikator_sasaran_strategis_tahun_mulai_nomor_unique',
        ]);
    }

    public function down(): void
    {
        $this->dropConstraintIfExists('sasaran_strategis', 'sasaran_strategis_tahun_mulai_nomor_unique');
        $this->dropConstraintIfExists('indikator_sasaran_strategis', 'indikator_sasaran_strategis_tahun_mulai_nomor_unique');
    }

    private function fixUniqueConstraint(string $table, array $constraints): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'tahun_mulai')) {
            return;
        }

        foreach ($constraints as $constraint) {
            $this->dropConstraintIfExists($table, $constraint);
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->unique(['tahun_mulai', 'nomor']);
        });
    }

    private function dropConstraintIfExists(string $table, string $constraint): void
    {
        DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$constraint}");
    }
};
