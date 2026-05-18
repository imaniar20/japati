<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $connection = 'ekinerja';

    public function up(): void
    {
        Schema::create('tim_kerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satuan_kerja_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('nama_tim')->nullable();
            $table->string('ketua')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_kerja');
    }
};
