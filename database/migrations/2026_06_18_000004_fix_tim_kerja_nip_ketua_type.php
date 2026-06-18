<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $connection = 'ekinerja';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('tim_kerja', 'nip_ketua')) {
            return;
        }

        DB::connection($this->connection)->statement("
            ALTER TABLE tim_kerja
            ALTER COLUMN nip_ketua TYPE VARCHAR(255)
            USING nip_ketua::VARCHAR
        ");
    }

    public function down(): void
    {
        // Kolom nip_ketua sengaja dipertahankan sebagai VARCHAR karena NIP adalah identifier teks.
    }
};
