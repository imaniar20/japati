<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $kinerjaTables = [
        'kinerja_program',
        'kinerja_kegiatan',
        'kinerja_sub_kegiatan',
        'kinerja_langkah_aksi',
    ];

    private array $masterTables = [
        'program',
        'kegiatan',
        'sub_kegiatan',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->masterTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->float('anggaran')->change();
            });
        }

        foreach ($this->kinerjaTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->float('anggaran')->change();
                $table->float('realisasi_anggaran')->default(0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ga usah di rollback
        // kenapa?
        // gapapa
    }
};
