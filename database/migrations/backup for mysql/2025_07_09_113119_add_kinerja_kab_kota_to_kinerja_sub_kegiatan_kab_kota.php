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
        Schema::table('kinerja_sub_kegiatan_kab_kota', function (Blueprint $table) {
            $table->unsignedBigInteger('kab_kota_id')->nullable();
            $table->unsignedBigInteger('kab_kota_satuan_kerja_id')->nullable();
            $table->unsignedBigInteger('kab_kota_kinerja_sub_kegiatan_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kinerja_sub_kegiatan_kab_kota', function (Blueprint $table) {
            $table->dropColumn(['kab_kota_id', 'kab_kota_satuan_kerja_id', 'kab_kota_kinerja_sub_kegiatan_id']);
        });
    }
};
