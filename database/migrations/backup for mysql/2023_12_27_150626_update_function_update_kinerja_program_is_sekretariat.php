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
        // Hapus trigger jika sudah ada
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_kinerja_program');
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_kinerja_program');

        // Trigger untuk INSERT
        DB::unprepared("
            CREATE TRIGGER before_insert_kinerja_program
            BEFORE INSERT ON kinerja_program
            FOR EACH ROW
            BEGIN
                -- Cek apakah program adalah PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI
                IF EXISTS (
                    SELECT 1
                    FROM program
                    WHERE id = NEW.program_id
                    AND nama = 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI'
                ) THEN
                    SET NEW.is_sekretariat = TRUE;
                ELSE
                    IF NEW.pengampu = 'unit-kerja' THEN
                        SET NEW.is_sekretariat = EXISTS (
                            SELECT 1
                            FROM v_struktur_organisasi
                            WHERE id = NEW.v_struktur_organisasi_id
                            AND unit_kerja_nama = 'SEKRETARIAT'
                        );
                    ELSE
                        SET NEW.is_sekretariat = FALSE;
                    END IF;
                END IF;
            END
        ");

        // Trigger untuk UPDATE
        DB::unprepared("
            CREATE TRIGGER before_update_kinerja_program
            BEFORE UPDATE ON kinerja_program
            FOR EACH ROW
            BEGIN
                -- Cek apakah program adalah PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI
                IF EXISTS (
                    SELECT 1
                    FROM program
                    WHERE id = NEW.program_id
                    AND nama = 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI'
                ) THEN
                    SET NEW.is_sekretariat = TRUE;
                ELSE
                    IF NEW.pengampu = 'unit-kerja' THEN
                        SET NEW.is_sekretariat = EXISTS (
                            SELECT 1
                            FROM v_struktur_organisasi
                            WHERE id = NEW.v_struktur_organisasi_id
                            AND unit_kerja_nama = 'SEKRETARIAT'
                        );
                    ELSE
                        SET NEW.is_sekretariat = FALSE;
                    END IF;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_kinerja_program');
        DB::unprepared('DROP TRIGGER IF EXISTS before_update_kinerja_program');
    }
};
