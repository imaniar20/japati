<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->renameColumn('validasi_bulanan', 'validasi_cascading');
            $table->json('validasi_cascading')->default('{ "status": null, "catatan": null }')->change();
        });

        // reset current value to default
        DB::table('kinerja_kegiatan')->update(['validasi_cascading' => '{ "status": null, "catatan": null }']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->renameColumn('validasi_cascading', 'validasi_bulanan');
            $table->json('validasi_bulanan')->default('{"jan": { "status": null, "catatan": null }, "feb": { "status": null, "catatan": null }, "mar": { "status": null, "catatan": null }, "apr": { "status": null, "catatan": null }, "may": { "status": null, "catatan": null }, "jun": { "status": null, "catatan": null }, "jul": { "status": null, "catatan": null }, "aug": { "status": null, "catatan": null }, "sep": { "status": null, "catatan": null }, "oct": { "status": null, "catatan": null }, "nov": { "status": null, "catatan": null }, "dec": { "status": null, "catatan": null }}')->change();
        });

        // reset current value to default
        DB::table('kinerja_kegiatan')->update(['validasi_bulanan' => '{"jan": { "status": null, "catatan": null }, "feb": { "status": null, "catatan": null }, "mar": { "status": null, "catatan": null }, "apr": { "status": null, "catatan": null }, "may": { "status": null, "catatan": null }, "jun": { "status": null, "catatan": null }, "jul": { "status": null, "catatan": null }, "aug": { "status": null, "catatan": null }, "sep": { "status": null, "catatan": null }, "oct": { "status": null, "catatan": null }, "nov": { "status": null, "catatan": null }, "dec": { "status": null, "catatan": null }}']);
    }
};
