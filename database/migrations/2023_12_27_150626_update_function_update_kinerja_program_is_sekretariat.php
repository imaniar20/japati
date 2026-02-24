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
        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_kinerja_program_is_sekretariat()
            RETURNS TRIGGER AS $$
            BEGIN
                IF EXISTS (
                    SELECT 1
                    FROM public.program
                    WHERE id = NEW.program_id
                    AND nama = 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI'
                ) THEN
                    NEW.is_sekretariat = true;
                ELSE
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
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
    }
};
