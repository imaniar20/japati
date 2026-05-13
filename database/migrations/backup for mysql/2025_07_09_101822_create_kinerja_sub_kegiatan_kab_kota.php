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
        Schema::create('kinerja_sub_kegiatan_kab_kota', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satuan_kerja_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('sub_kegiatan_id')->nullable();
            $table->text('sasaran')->nullable();
            $table->text('indikator');
            $table->string('satuan', 255);
            $table->double('target');
            $table->json('target_bulanan');
            $table->double('realisasi');
            $table->double('anggaran');
            $table->json('anggaran_bulanan');
            $table->boolean('has_inovasi');
            $table->text('inovasi_uraian')->nullable();
            $table->text('inovasi_tujuan')->nullable();
            $table->json('inovasi_lampiran')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->json('realisasi_bulanan')->default('{"apr": null, "aug": null, "dec": null, "feb": null, "jan": null, "jul": null, "jun": null, "mar": null, "may": null, "nov": null, "oct": null, "sep": null}');
            $table->json('realisasi_anggaran_bulanan')->default('{"apr": 0, "aug": 0, "dec": 0, "feb": 0, "jan": 0, "jul": 0, "jun": 0, "mar": 0, "may": 0, "nov": 0, "oct": 0, "sep": 0}');
            $table->double('capaian')->nullable();
            $table->double('realisasi_anggaran')->default(0);
            $table->double('capaian_anggaran')->nullable();
            $table->unsignedBigInteger('sasaran_strategis_rpjmd_id')->nullable();
            $table->unsignedBigInteger('kinerja_program_id')->nullable();
            $table->unsignedBigInteger('kinerja_kegiatan_id')->nullable();
            $table->unsignedBigInteger('sasaran_strategis_pd_id')->nullable();
            $table->integer('tahun_kinerja')->nullable();
            $table->string('v_struktur_organisasi_id', 255)->nullable();
            $table->string('pengampu', 255)->nullable();
            $table->unsignedBigInteger('tim_kerja_id')->nullable();
            $table->text('penyebab_kegagalan')->nullable();
            $table->text('indikator_kemendagri')->nullable();
            $table->boolean('is_kemiskinan')->default(false);
            $table->boolean('is_sekretariat')->default(false);
            $table->boolean('is_stunting')->default(false);
            $table->boolean('is_inflasi')->default(false);
            $table->boolean('is_investasi')->default(false);
            $table->boolean('is_penggunaan_pdn')->default(false);
            $table->text('rencana_aksi')->nullable();
            $table->boolean('is_external')->default(false);
            $table->boolean('is_rencana_aksi_gubernur')->default(false);
            $table->json('eviden_bulanan')->default('{"apr": null, "aug": null, "dec": null, "feb": null, "jan": null, "jul": null, "jun": null, "mar": null, "may": null, "nov": null, "oct": null, "sep": null}');
            $table->json('validasi_bulanan')->default('{"apr": {"status": null, "catatan": null}, "aug": {"status": null, "catatan": null}, "dec": {"status": null, "catatan": null}, "feb": {"status": null, "catatan": null}, "jan": {"status": null, "catatan": null}, "jul": {"status": null, "catatan": null}, "jun": {"status": null, "catatan": null}, "mar": {"status": null, "catatan": null}, "may": {"status": null, "catatan": null}, "nov": {"status": null, "catatan": null}, "oct": {"status": null, "catatan": null}, "sep": {"status": null, "catatan": null}}');
            $table->text('do_narasi')->nullable();
            $table->text('do_rumus')->nullable();
            $table->text('do_keterangan')->nullable();
            $table->text('do_sumber')->nullable();
            $table->json('validasi_pengampu')->default('{"status": null, "catatan": null}');

            // Indexes
            $table->index('kegiatan_id', 'kinerja_sub_kegiatan_kab_kota_kegiatan_id_index');
            $table->index('kinerja_kegiatan_id', 'kinerja_sub_kegiatan_kinerja_kab_kota_kegiatan_id_index');
            $table->index('kinerja_program_id', 'kinerja_sub_kegiatan_kab_kota_kinerja_program_id_index');
            $table->index('sasaran_strategis_rpjmd_id', 'kinerja_sub_kegiatan_kab_kota_rpjmd_sasaran_strategis_id_index');
            $table->index('sasaran_strategis_pd_id', 'kinerja_sub_kegiatan_kab_kota_satker_sasaran_strategis_id_index');
            $table->index('satuan_kerja_id', 'kinerja_sub_kegiatan_kab_kota_satuan_kerja_id_index');
            $table->index('sub_kegiatan_id', 'kinerja_sub_kegiatan_kab_kota_sub_kegiatan_id_index');
            $table->index(['tahun_kinerja', 'satuan_kerja_id'], 'kinerja_sub_kegiatan_kab_kota_tahun_kinerja_satuan_kerja_id_index');
            $table->index('tim_kerja_id', 'kinerja_sub_kegiatan_kab_kota_tim_kerja_id_index');
            $table->index('v_struktur_organisasi_id', 'kinerja_sub_kegiatan_kab_kota_v_struktur_organisasi_id_index');
        });

        // Note: The trigger functionality would need to be implemented in Laravel using Eloquent events
        // or database triggers if needed. Laravel doesn't have direct migration support for triggers.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kinerja_sub_kegiatan_kab_kota');
    }
};
