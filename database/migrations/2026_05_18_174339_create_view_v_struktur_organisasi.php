<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_struktur_organisasi AS
            SELECT
                satuan_kerja_id::varchar as id,

                satuan_kerja_id,

                satuan_kerja_nama,

                satuan_kerja_nama as unit_kerja_nama,

                satuan_kerja_nama as lv1_unit_kerja_nama,

                satuan_kerja_id as lv2_unit_kerja_id,

                satuan_kerja_nama_alias as jabatan_nama,

                1 as level,

                status,

                NULL::timestamp as unit_kerja_aktif_selesai

            FROM satuan_kerja
        ");
    }

    public function down(): void
    {
        DB::statement("
            DROP VIEW IF EXISTS v_struktur_organisasi
        ");
    }
};
