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
        Schema::create('solusi_kinerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('masalah_id');
            $table->string('masalah_type');
            $table->unsignedBigInteger('solusi_id');
            $table->string('solusi_type');
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
        Schema::dropIfExists('solusi_kinerja');
    }
};
