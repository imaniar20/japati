<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $connection = 'ekinerja';

    public function up(): void
    {
        Schema::create('pegawai_data', function (Blueprint $table) {
            $table->id();

            $table->string('peg_nip')->unique()->nullable();
            $table->unsignedBigInteger('id_satuan_kerja')->nullable();
            $table->string('peg_nama')->nullable();
            $table->string('jabatan_nama')->nullable();
            $table->string('unit_kerja_nama')->nullable();
            $table->string('peg_status')->nullable();

            $table->foreign('id_satuan_kerja')
                ->references('satuan_kerja_id')
                ->on('satuan_kerja')
                ->nullOnDelete();
                

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai_data');
    }
};
