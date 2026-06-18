<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $connection = 'ekinerja';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('tim_kerja', 'v_struktur_organisasi_id')) {
            return;
        }

        DB::connection($this->connection)->statement("
            ALTER TABLE tim_kerja
            ALTER COLUMN v_struktur_organisasi_id TYPE VARCHAR(255)
            USING v_struktur_organisasi_id::VARCHAR
        ");
    }

    public function down(): void
    {
        // Kolom ini sengaja dipertahankan sebagai VARCHAR agar sesuai dengan v_struktur_organisasi.id.
    }
};
