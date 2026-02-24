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
            $table->jsonb('eviden_bulanan')->default('{"apr": null, "aug": null, "dec": null, "feb": null, "jan": null, "jul": null, "jun": null, "mar": null, "may": null, "nov": null, "oct": null, "sep": null}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn('eviden_bulanan');
        });
    }
};
