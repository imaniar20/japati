<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lke_kriteria', function (Blueprint $table) {
            $table->string('nomor_full')->nullable()->comment('kolom ini sudah otomatis diisi oleh sistem');
        });

        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_lke_kriteria_nomor_full()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.nomor_full = (
                    SELECT lke_komponen.nomor || '.' || lke_sub_komponen.nomor || '.' || NEW.nomor
                    FROM lke_sub_komponen
                    JOIN lke_komponen ON lke_sub_komponen.komponen_id = lke_komponen.id
                    WHERE lke_sub_komponen.id = NEW.sub_komponen_id
                );
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;        
        ");

        DB::unprepared('
            CREATE TRIGGER trg_update_nomor_full_on_lke_kriteria
            BEFORE INSERT OR UPDATE ON public.lke_kriteria
            FOR EACH ROW
            EXECUTE PROCEDURE update_lke_kriteria_nomor_full();        
        ');

        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_nomor_full_due_to_lke_sub_komponen_change()
            RETURNS TRIGGER AS $$
            BEGIN
                UPDATE lke_kriteria
                SET nomor_full = null
                WHERE sub_komponen_id = NEW.id;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trg_update_nomor_full_on_lke_sub_komponen
            AFTER INSERT OR UPDATE ON public.lke_sub_komponen
            FOR EACH ROW
            EXECUTE PROCEDURE update_nomor_full_due_to_lke_sub_komponen_change();
        ');

        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_nomor_full_due_to_lke_komponen_change()
            RETURNS TRIGGER AS $$
            BEGIN
                UPDATE lke_kriteria
                SET nomor_full = null
                WHERE sub_komponen_id IN (
                    SELECT id FROM lke_sub_komponen WHERE komponen_id = NEW.id
                );
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trg_update_nomor_full_on_lke_komponen
            AFTER INSERT OR UPDATE ON public.lke_komponen
            FOR EACH ROW
            EXECUTE PROCEDURE update_nomor_full_due_to_lke_komponen_change();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_update_nomor_full_on_lke_kriteria ON public.lke_kriteria;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_update_nomor_full_on_lke_sub_komponen ON public.lke_sub_komponen;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_update_nomor_full_on_lke_komponen ON public.lke_komponen;');

        DB::unprepared('DROP FUNCTION IF EXISTS update_lke_kriteria_nomor_full();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_nomor_full_due_to_lke_sub_komponen_change();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_nomor_full_due_to_lke_komponen_change();');

        Schema::table('lke_kriteria', function (Blueprint $table) {
            $table->dropColumn('nomor_full');
        });
    }
};
