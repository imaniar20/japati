<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION set_kinerja_sub_kegiatan_is_sekretariat_when_kinerja_sub_kegiatan_updated()
            RETURNS TRIGGER AS $$
            BEGIN
                SELECT is_sekretariat INTO NEW.is_sekretariat
                FROM public.kinerja_kegiatan
                WHERE id = NEW.kinerja_kegiatan_id;

                NEW.is_sekretariat := COALESCE(NEW.is_sekretariat, false);
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
