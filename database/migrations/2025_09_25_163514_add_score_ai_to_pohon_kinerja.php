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
        Schema::table('pohon_kinerja', function (Blueprint $table) {
            $table->string('label_ai')->nullable();
            $table->float('score_ai')->nullable();
            $table->text('reasoning_korelasi_ai')->nullable();
            $table->text('reasoning_kinerja_ai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pohon_kinerja', function (Blueprint $table) {
            $table->dropColumn(['label_ai', 'score_ai', 'reasoning_korelasi_ai', 'reasoning_kinerja_ai']);
        });
    }
};
