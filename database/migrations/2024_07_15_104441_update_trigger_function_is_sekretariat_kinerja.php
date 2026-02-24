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
        DB::unprepared('DROP TRIGGER IF EXISTS update_kinerja_kegiatan_is_sekretariat ON public.kinerja_program;');
        DB::unprepared('DROP TRIGGER IF EXISTS update_kinerja_sub_kegiatan_is_sekretariat ON public.kinerja_kegiatan;');

        DB::unprepared('DROP FUNCTION IF EXISTS update_kinerja_kegiatan_is_sekretariat();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_kinerja_sub_kegiatan_is_sekretariat();');

        // Procedure for kinerja_program
        DB::unprepared('
            CREATE OR REPLACE FUNCTION set_kinerja_kegiatan_is_sekretariat_when_kinerja_program_updated()
            RETURNS TRIGGER AS $$
            BEGIN
                UPDATE public.kinerja_kegiatan
                SET is_sekretariat = NEW.is_sekretariat
                WHERE kinerja_program_id = NEW.id;
                
                UPDATE public.kinerja_sub_kegiatan
                SET is_sekretariat = NEW.is_sekretariat
                WHERE kinerja_kegiatan_id IN (
                    SELECT id FROM public.kinerja_kegiatan
                    WHERE kinerja_program_id = NEW.id
                );
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Trigger for kinerja_program
        DB::unprepared('
            CREATE TRIGGER trigger_set_kinerja_kegiatan_is_sekretariat_when_kinerja_program_updated
            AFTER UPDATE ON public.kinerja_program
            FOR EACH ROW
            EXECUTE PROCEDURE set_kinerja_kegiatan_is_sekretariat_when_kinerja_program_updated();
        ');

        // Procedure for kinerja_kegiatan
        DB::unprepared('
            CREATE OR REPLACE FUNCTION set_kinerja_kegiatan_and_sub_kegiatan_is_sekretariat_when_kinerja_kegiatan_updated()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Update self based on kinerja_program
                SELECT is_sekretariat INTO NEW.is_sekretariat
                FROM public.kinerja_program
                WHERE id = NEW.kinerja_program_id;

                -- Update kinerja_sub_kegiatan
                UPDATE public.kinerja_sub_kegiatan
                SET is_sekretariat = NEW.is_sekretariat
                WHERE kinerja_kegiatan_id = NEW.id;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Trigger for kinerja_kegiatan
        DB::unprepared('
            CREATE TRIGGER trigger_set_kinerja_kegiatan_and_sub_kegiatan_is_sekretariat_when_kinerja_kegiatan_updated
            BEFORE UPDATE ON public.kinerja_kegiatan
            FOR EACH ROW
            EXECUTE PROCEDURE set_kinerja_kegiatan_and_sub_kegiatan_is_sekretariat_when_kinerja_kegiatan_updated();
        ');

        // Procedure for kinerja_sub_kegiatan
        DB::unprepared('
            CREATE OR REPLACE FUNCTION set_kinerja_sub_kegiatan_is_sekretariat_when_kinerja_sub_kegiatan_updated()
            RETURNS TRIGGER AS $$
            BEGIN
                SELECT is_sekretariat INTO NEW.is_sekretariat
                FROM public.kinerja_kegiatan
                WHERE id = NEW.kinerja_kegiatan_id;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Trigger for kinerja_sub_kegiatan
        DB::unprepared('
            CREATE TRIGGER trigger_set_kinerja_sub_kegiatan_is_sekretariat_when_kinerja_sub_kegiatan_updated
            BEFORE UPDATE ON public.kinerja_sub_kegiatan
            FOR EACH ROW
            EXECUTE PROCEDURE set_kinerja_sub_kegiatan_is_sekretariat_when_kinerja_sub_kegiatan_updated();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_kinerja_kegiatan_is_sekretariat()
            RETURNS TRIGGER AS $$
            BEGIN
                IF TG_OP = 'INSERT' OR NEW.is_sekretariat <> OLD.is_sekretariat THEN
                    UPDATE public.kinerja_kegiatan
                    SET is_sekretariat = NEW.is_sekretariat
                    WHERE kinerja_program_id = NEW.id;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::unprepared('
            CREATE TRIGGER update_kinerja_kegiatan_is_sekretariat
            AFTER INSERT OR UPDATE
            ON public.kinerja_program
            FOR EACH ROW
            EXECUTE PROCEDURE update_kinerja_kegiatan_is_sekretariat();
        ');

        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_kinerja_sub_kegiatan_is_sekretariat()
            RETURNS TRIGGER AS $$
            BEGIN
                IF TG_OP = 'INSERT' OR NEW.is_sekretariat <> OLD.is_sekretariat THEN
                    UPDATE public.kinerja_sub_kegiatan
                    SET is_sekretariat = NEW.is_sekretariat
                    WHERE kinerja_kegiatan_id = NEW.id;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::unprepared('
            CREATE TRIGGER update_kinerja_sub_kegiatan_is_sekretariat
            AFTER INSERT OR UPDATE
            ON public.kinerja_kegiatan
            FOR EACH ROW
            EXECUTE PROCEDURE update_kinerja_sub_kegiatan_is_sekretariat();
        ');

        // Remove triggers
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_set_kinerja_kegiatan_is_sekretariat_when_kinerja_program_updated ON public.kinerja_program;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_set_kinerja_kegiatan_and_sub_kegiatan_is_sekretariat_when_kinerja_kegiatan_updated ON public.kinerja_kegiatan;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_set_kinerja_sub_kegiatan_is_sekretariat_when_kinerja_sub_kegiatan_updated ON public.kinerja_sub_kegiatan;');

        // Remove functions
        DB::unprepared('DROP FUNCTION IF EXISTS set_kinerja_kegiatan_is_sekretariat_when_kinerja_program_updated();');
        DB::unprepared('DROP FUNCTION IF EXISTS set_kinerja_kegiatan_and_sub_kegiatan_is_sekretariat_when_kinerja_kegiatan_updated();');
        DB::unprepared('DROP FUNCTION IF EXISTS set_kinerja_sub_kegiatan_is_sekretariat_when_kinerja_sub_kegiatan_updated();');
    }
};
