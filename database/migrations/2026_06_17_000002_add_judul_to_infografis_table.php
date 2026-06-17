<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('infografis', 'judul')) {
            Schema::table('infografis', function (Blueprint $table) {
                $table->string('judul', 255)->nullable()->after('tahun_kinerja');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('infografis', 'judul')) {
            Schema::table('infografis', function (Blueprint $table) {
                $table->dropColumn('judul');
            });
        }
    }
};
