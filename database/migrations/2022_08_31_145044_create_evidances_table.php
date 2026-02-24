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
        Schema::create('lke_eviden', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satuan_kerja_id');
            $table->unsignedInteger('tahun_kinerja');
            $table->foreignId('kriteria_id')->constrained('lke_kriteria');
            $table->foreignId('parameter_id')->nullable()->constrained('lke_parameter');
            $table->jsonb('eviden')->nullable();
            $table->timestamps();

            $table->index(['tahun_kinerja', 'satuan_kerja_id', 'kriteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lke_eviden');
    }
};
