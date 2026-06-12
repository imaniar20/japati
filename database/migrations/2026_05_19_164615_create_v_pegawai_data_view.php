<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    // public $connection = 'ekinerja';

    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_pegawai_data AS
            SELECT
                uk.id,
                uk.peg_nip,
                uk.id_satuan_kerja as satuan_kerja_id,
                uk.peg_nama,
                uk.jabatan_nama,
                uk.unit_kerja_nama,
                uk.peg_status
            FROM pegawai_data uk
            -- sesuaikan JOIN dan kondisi dengan skema aslimu
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_pegawai_data");
    }
};
