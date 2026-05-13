<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableRpjmdVisiMisiToVisiMisiRpjmd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('rpjmd_visi_misi', 'visi_misi_rpjmd');
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->renameColumn('target_rpjmd_visi_misi_id', 'target_visi_misi_rpjmd_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('visi_misi_rpjmd', 'rpjmd_visi_misi');
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            $table->renameColumn('target_visi_misi_rpjmd_id', 'target_rpjmd_visi_misi_id');
        });
    }
}
