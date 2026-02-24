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
        Schema::create('pohon_kinerja_raw2', function (Blueprint $table) {
            $table->id();
            $table->text('satuan_kerja');
            $table->text('sasaran');
            $table->text('indikator')->nullable();
            $table->boolean('is_crosscutting')->nullable();
            $table->text('why')->nullable();
            $table->string('status_lv')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedInteger('tahun_kinerja')->nullable();
            $table->unsignedBigInteger('satuan_kerja_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pohon_kinerja_raw2');
    }
};
