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
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->jsonb('validasi_bulanan')->default('{"apr": { "status": null, "catatan": null }, "aug": { "status": null, "catatan": null }, "dec": { "status": null, "catatan": null }, "feb": { "status": null, "catatan": null }, "jan": { "status": null, "catatan": null }, "jul": { "status": null, "catatan": null }, "jun": { "status": null, "catatan": null }, "mar": { "status": null, "catatan": null }, "may": { "status": null, "catatan": null }, "nov": { "status": null, "catatan": null }, "oct": { "status": null, "catatan": null }, "sep": { "status": null, "catatan": null }}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn('validasi_bulanan');
        });
    }
};
