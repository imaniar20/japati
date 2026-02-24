<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lke_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_komponen_id')->constrained('lke_sub_komponen')->cascadeOnDelete();
            $table->string('nama');
            $table->float('bobot');
            $table->boolean('is_auto');
            $table->boolean('is_locked');
            $table->unsignedInteger('jumlah_eviden');
            $table->jsonb('keterangan_eviden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lke_kriteria');
    }
};
