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
        Schema::create('lke_riwayat_eviden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->nullable()->constrained('lke_penilaian');
            $table->foreignId('eviden_id')->constrained('lke_eviden');
            $table->foreignId('parameter_id')->nullable()->constrained('lke_parameter'); // nilai sebelumnya
            $table->jsonb('eviden')->nullable();
            $table->foreignId('nilai')->nullable()->constrained('lke_parameter'); // hasil penilaian
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('lke_riwayat_eviden');
    }
};
