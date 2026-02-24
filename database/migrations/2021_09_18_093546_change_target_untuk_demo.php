<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTargetUntukDemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Artisan::call('db:seed', [
        //     '--class' => 'ConvertTargetToFloat'
        // ]);

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
            'target',
            'realisasi',
        ];

        foreach ($cols as $col) {
            DB::statement("ALTER TABLE kinerja_kegiatan ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }
        foreach ($cols as $col) {
            DB::statement("ALTER TABLE kinerja_sub_kegiatan ALTER {$col} TYPE DOUBLE PRECISION USING {$col}::double precision");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('satker_sasaran_strategis', function (Blueprint $table) {
            $table->string('target_baseline')->change();
            $table->string('target_1')->change();
            $table->string('target_2')->change();
            $table->string('target_3')->change();
            $table->string('target_4')->change();
            $table->string('target_5')->change();
            $table->string('realisasi_baseline')->change();
            $table->string('realisasi_1')->change();
            $table->string('realisasi_2')->change();
            $table->string('realisasi_3')->change();
            $table->string('realisasi_4')->change();
            $table->string('realisasi_5')->change();
        });
        Schema::table('kinerja_kegiatan', function (Blueprint $table) {
            $table->string('target')->change();
            $table->string('realisasi')->change();
        });
        Schema::table('kinerja_sub_kegiatan', function (Blueprint $table) {
            $table->string('target')->change();
            $table->string('realisasi')->change();
        });
    }
}
