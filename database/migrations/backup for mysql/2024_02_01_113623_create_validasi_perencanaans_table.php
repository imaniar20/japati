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
        Schema::create('validasi_perencanaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedInteger('tahun_kinerja')->index();
            $table->unsignedInteger('tahap');
            $table->boolean('status')->nullable()->comment('true: terima, false: tolak, null: belum divalidasi');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['satuan_kerja_id', 'tahun_kinerja']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi_perencanaan');
    }
};
