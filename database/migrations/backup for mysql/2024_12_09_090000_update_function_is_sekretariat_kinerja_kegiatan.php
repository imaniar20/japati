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
            CREATE OR REPLACE FUNCTION set_kinerja_kegiatan_and_sub_kegiatan_is_sekretariat_when_kinerja_kegiatan_updated()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Update self based on kinerja_program
                SELECT is_sekretariat INTO NEW.is_sekretariat
                FROM public.kinerja_program
                WHERE id = NEW.kinerja_program_id;

                NEW.is_sekretariat := COALESCE(NEW.is_sekretariat, false);

                -- Update kinerja_sub_kegiatan
                UPDATE public.kinerja_sub_kegiatan
                SET is_sekretariat = NEW.is_sekretariat
                WHERE kinerja_kegiatan_id = NEW.id;
                
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
