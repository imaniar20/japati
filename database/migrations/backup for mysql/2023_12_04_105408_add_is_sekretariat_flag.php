<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambahkan kolom ke tabel kinerja_program
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->boolean('is_sekretariat')->default(false)->after('pengampu');
        });

        // Tambahkan kolom ke tabel kinerja_kegiatan
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->boolean('is_sekretariat')->default(false)->after('pengampu');
        });

        // Tambahkan kolom ke tabel kinerja_sub_kegiatan
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->boolean('is_sekretariat')->default(false)->after('pengampu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kinerja_program', function (Blueprint $table) {
            $table->dropColumn('is_sekretariat');
        });

        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->dropColumn('is_sekretariat');
        });

        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn('is_sekretariat');
        });
    }
};
