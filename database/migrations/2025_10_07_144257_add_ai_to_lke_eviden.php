<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lke_riwayat_eviden', function (Blueprint $table) {
            $table->float('score_ai')->nullable();
            $table->string('label_ai')->nullable();
            $table->text('reasoning_ai')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lke_riwayat_eviden', function (Blueprint $table) {
            $table->dropColumn(['score_ai', 'label_ai', 'reasoning_ai']);
        });
    }
};
