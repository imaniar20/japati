<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infografis', function (Blueprint $table) {
            $table->unsignedInteger('tahun_kinerja')->nullable()->after('id')->index();
        });

        DB::table('infografis')
            ->whereNull('tahun_kinerja')
            ->update([
                'tahun_kinerja' => defined('TAHUN_KINERJA') ? TAHUN_KINERJA : 2025,
            ]);
    }

    public function down(): void
    {
        Schema::table('infografis', function (Blueprint $table) {
            $table->dropColumn('tahun_kinerja');
        });
    }
};
