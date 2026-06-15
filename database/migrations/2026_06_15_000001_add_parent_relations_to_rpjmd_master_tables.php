<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('misi', function (Blueprint $table) {
            $table->foreignId('visi_id')->nullable()->constrained('visi')->nullOnDelete();
        });

        Schema::table('tujuan', function (Blueprint $table) {
            $table->foreignId('misi_id')->nullable()->constrained('misi')->nullOnDelete();
        });

        Schema::table('indikator_tujuan', function (Blueprint $table) {
            $table->foreignId('tujuan_id')->nullable()->constrained('tujuan')->nullOnDelete();
        });

        $this->dropConstraintIfExists('misi', 'misi_nomor_unique');
        $this->dropConstraintIfExists('misi', 'misi_tahun_mulai_nomor_unique');
        $this->dropConstraintIfExists('tujuan', 'tujuan_nomor_unique');
        $this->dropConstraintIfExists('tujuan', 'tujuan_tahun_mulai_nomor_unique');
        $this->dropConstraintIfExists('indikator_tujuan', 'tujuan_indikator_nomor_unique');
        $this->dropConstraintIfExists('indikator_tujuan', 'indikator_tujuan_nomor_unique');
        $this->dropConstraintIfExists('indikator_tujuan', 'indikator_tujuan_tahun_mulai_nomor_unique');

        Schema::table('misi', function (Blueprint $table) {
            $table->unique(['tahun_mulai', 'visi_id', 'nomor']);
        });

        Schema::table('tujuan', function (Blueprint $table) {
            $table->unique(['tahun_mulai', 'misi_id', 'nomor']);
        });

        Schema::table('indikator_tujuan', function (Blueprint $table) {
            $table->unique(['tahun_mulai', 'tujuan_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::table('indikator_tujuan', function (Blueprint $table) {
            $table->dropUnique(['tahun_mulai', 'tujuan_id', 'nomor']);
            $table->dropConstrainedForeignId('tujuan_id');
        });

        Schema::table('tujuan', function (Blueprint $table) {
            $table->dropUnique(['tahun_mulai', 'misi_id', 'nomor']);
            $table->dropConstrainedForeignId('misi_id');
        });

        Schema::table('misi', function (Blueprint $table) {
            $table->dropUnique(['tahun_mulai', 'visi_id', 'nomor']);
            $table->dropConstrainedForeignId('visi_id');
        });
    }

    private function dropConstraintIfExists(string $table, string $constraint): void
    {
        DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$constraint}");
    }
};
