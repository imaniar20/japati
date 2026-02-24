<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'kinerja_program',
        'kinerja_kegiatan',
        'kinerja_sub_kegiatan',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->boolean('is_sekretariat')->default(false);
            });
        }

        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_kinerja_program_is_sekretariat()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.pengampu = 'unit-kerja' THEN
                    NEW.is_sekretariat = EXISTS (
                        SELECT 1
                        FROM public.v_struktur_organisasi
                        WHERE id = NEW.v_struktur_organisasi_id
                        AND unit_kerja_nama = 'SEKRETARIAT'
                    );
                ELSE
                    NEW.is_sekretariat = false;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::unprepared('
            CREATE TRIGGER update_kinerja_program_is_sekretariat
            BEFORE INSERT OR UPDATE
            ON public.kinerja_program
            FOR EACH ROW
            EXECUTE PROCEDURE update_kinerja_program_is_sekretariat();
        ');

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('is_sekretariat');
            });
        }

        DB::unprepared('DROP TRIGGER IF EXISTS update_kinerja_program_is_sekretariat ON public.kinerja_program;');
        DB::unprepared('DROP TRIGGER IF EXISTS update_kinerja_kegiatan_is_sekretariat ON public.kinerja_program;');
        DB::unprepared('DROP TRIGGER IF EXISTS update_kinerja_sub_kegiatan_is_sekretariat ON public.kinerja_kegiatan;');

        DB::unprepared('DROP FUNCTION IF EXISTS update_kinerja_program_is_sekretariat();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_kinerja_kegiatan_is_sekretariat();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_kinerja_sub_kegiatan_is_sekretariat();');
    }
};
