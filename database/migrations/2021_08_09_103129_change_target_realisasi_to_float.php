<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTargetRealisasiToFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpjmd_visi_misi', function (Blueprint $table) {
            $table->string('target_baseline')->change();
            $table->string('target_1')->change();
            $table->string('target_2')->change();
            $table->string('target_3')->change();
            $table->string('target_4')->change();
            $table->string('target_5')->change();
            $table->string('realisasi_baseline')->nullable()->change();
            $table->string('realisasi_1')->nullable()->change();
            $table->string('realisasi_2')->nullable()->change();
            $table->string('realisasi_3')->nullable()->change();
            $table->string('realisasi_4')->nullable()->change();
            $table->string('realisasi_5')->nullable()->change();
        });

        Schema::table('rpjmd_sasaran_strategis', function (Blueprint $table) {
            $table->string('target_baseline')->change();
            $table->string('target_1')->change();
            $table->string('target_2')->change();
            $table->string('target_3')->change();
            $table->string('target_4')->change();
            $table->string('target_5')->change();
            $table->string('realisasi_baseline')->nullable()->change();
            $table->string('realisasi_1')->nullable()->change();
            $table->string('realisasi_2')->nullable()->change();
            $table->string('realisasi_3')->nullable()->change();
            $table->string('realisasi_4')->nullable()->change();
            $table->string('realisasi_5')->nullable()->change();
        });

        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->string('target_baseline')->change();
            $table->string('target_1')->change();
            $table->string('target_2')->change();
            $table->string('target_3')->change();
            $table->string('target_4')->change();
            $table->string('target_5')->change();
            $table->string('realisasi_baseline')->nullable()->change();
            $table->string('realisasi_1')->nullable()->change();
            $table->string('realisasi_2')->nullable()->change();
            $table->string('realisasi_3')->nullable()->change();
            $table->string('realisasi_4')->nullable()->change();
            $table->string('realisasi_5')->nullable()->change();
        });

        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->string('target_tahunan')->change();
            $table->string('realisasi_tahunan')->change();
        });

        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->string('target_tahunan')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $cols = [
            'target_baseline',
            'target_1',
            'target_2',
            'target_3',
            'target_4',
            'target_5',
            'realisasi_baseline',
            'realisasi_1',
            'realisasi_2',
            'realisasi_3',
            'realisasi_4',
            'realisasi_5',
        ];

        foreach ($cols as $col) {
            DB::statement("ALTER TABLE rpjmd_visi_misi ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }

        $cols = [
            'target_baseline',
            'target_1',
            'target_2',
            'target_3',
            'target_4',
            'target_5',
            'realisasi_baseline',
            'realisasi_1',
            'realisasi_2',
            'realisasi_3',
            'realisasi_4',
            'realisasi_5',
        ];

        foreach ($cols as $col) {
            DB::statement("ALTER TABLE rpjmd_sasaran_strategis ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }

        $cols = [
            'target_baseline',
            'target_1',
            'target_2',
            'target_3',
            'target_4',
            'target_5',
            'realisasi_baseline',
            'realisasi_1',
            'realisasi_2',
            'realisasi_3',
            'realisasi_4',
            'realisasi_5',
        ];

        foreach ($cols as $col) {
            DB::statement("ALTER TABLE satker_sasaran_strategis ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }

        $cols = [
            'target_tahunan',
            'realisasi_tahunan',
        ];

        foreach ($cols as $col) {
            DB::statement("ALTER TABLE kinerja_sub_kegiatan ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }

        $cols = [
            'target_tahunan',
        ];

        foreach ($cols as $col) {
            DB::statement("ALTER TABLE kinerja_langkah_aksi ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }

        Schema::table('kinerja_langkah_aksi', function (Blueprint $table) {
            $table->float('target_tahunan')->change();
        });
    }
}
