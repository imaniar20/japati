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
        Schema::create('lke_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('lke_kriteria')->cascadeOnDelete();
            $table->string('nama');
            $table->float('skor');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('lke_parameter');
    }
};
