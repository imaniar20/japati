<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuanKerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satuan_kerja', function (Blueprint $table) {
            $table->unsignedBigInteger('satuan_kerja_id')->index();
            $table->unsignedInteger('tahun_id')->nullable();
            $table->string('satuan_kerja_nama')->nullable();
            $table->string('kode')->nullable();
            $table->string('satuan_kerja_alamat')->nullable();
            $table->string('satuan_kerja_kel_ds')->nullable();
            $table->unsignedInteger('kecamatan_id')->nullable();
            $table->string('satuan_kerja_khusus')->nullable();
            $table->unsignedInteger('status')->nullable();
            $table->string('kode_skpd')->nullable();
            $table->string('create_username')->nullable();
            $table->string('update_username')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('satuan_kerja_nama_alias')->nullable();
            $table->string('sapk_id')->nullable();
            $table->float('bobot')->nullable();
            $table->unsignedInteger('m_kabkot_id')->nullable();
            $table->unsignedInteger('rumpun_id')->nullable();
            $table->float('lampiran_no')->nullable();
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
        Schema::dropIfExists('satuan_kerja');
    }
}
