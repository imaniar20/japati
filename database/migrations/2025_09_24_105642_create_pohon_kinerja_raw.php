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
        Schema::create('pohon_kinerja_raw', function (Blueprint $table) {
            $table->id();
            $table->text('satuan_kerja');
            $table->text('sasaran_lv1');
            $table->text('indikator_lv1');
            $table->text('sasaran_lv2')->nullable();
            $table->text('indikator_lv2')->nullable();
            $table->text('is_crosscutting_lv2')->nullable();
            $table->text('sasaran_lv3')->nullable();
            $table->text('indikator_lv3')->nullable();
            $table->text('is_crosscutting_lv3')->nullable();
            $table->text('sasaran_lv4')->nullable();
            $table->text('indikator_lv4')->nullable();
            $table->text('is_crosscutting_lv4')->nullable();
            $table->text('sasaran_lv5')->nullable();
            $table->text('indikator_lv5')->nullable();
            $table->text('is_crosscutting_lv5')->nullable();
            $table->text('sasaran_lv6')->nullable();
            $table->text('indikator_lv6')->nullable();
            $table->text('is_crosscutting_lv6')->nullable();
            $table->text('sasaran_lv7')->nullable();
            $table->text('indikator_lv7')->nullable();
            $table->text('is_crosscutting_lv7')->nullable();
            $table->text('sasaran_lv8')->nullable();
            $table->text('indikator_lv8')->nullable();
            $table->text('is_crosscutting_lv8')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pohon_kinerja_raw');
    }
};
