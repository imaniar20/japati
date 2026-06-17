<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('infografis', 'id')) {
            Schema::table('infografis', function (Blueprint $table) {
                $table->id();
            });

            return;
        }

        DB::statement('CREATE SEQUENCE IF NOT EXISTS infografis_id_seq');
        DB::statement("ALTER TABLE infografis ALTER COLUMN id SET DEFAULT nextval('infografis_id_seq')");
        DB::statement("UPDATE infografis SET id = nextval('infografis_id_seq') WHERE id IS NULL");
        DB::statement("SELECT setval('infografis_id_seq', GREATEST(COALESCE((SELECT MAX(id) FROM infografis), 0), 1), true)");
        DB::statement('ALTER SEQUENCE infografis_id_seq OWNED BY infografis.id');
        DB::statement('ALTER TABLE infografis ALTER COLUMN id SET NOT NULL');
        DB::statement("
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM pg_constraint
                    WHERE conrelid = 'infografis'::regclass
                    AND contype = 'p'
                ) THEN
                    ALTER TABLE infografis ADD PRIMARY KEY (id);
                END IF;
            END $$;
        ");
    }

    public function down(): void
    {
        //
    }
};
