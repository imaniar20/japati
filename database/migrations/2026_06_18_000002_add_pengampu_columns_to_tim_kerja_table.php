<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $connection = 'ekinerja';

    public function up(): void
    {
        Schema::connection($this->connection)->table('tim_kerja', function (Blueprint $table) {
            if (! Schema::connection($this->connection)->hasColumn('tim_kerja', 'v_struktur_organisasi_id')) {
                $table->string('v_struktur_organisasi_id')->nullable()->after('satuan_kerja_id');
                $table->index('v_struktur_organisasi_id');
            }

            if (! Schema::connection($this->connection)->hasColumn('tim_kerja', 'nip_ketua')) {
                $table->string('nip_ketua')->nullable()->after('ketua');
                $table->index('nip_ketua');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('tim_kerja', function (Blueprint $table) {
            if (Schema::connection($this->connection)->hasColumn('tim_kerja', 'nip_ketua')) {
                $table->dropIndex(['nip_ketua']);
                $table->dropColumn('nip_ketua');
            }

            if (Schema::connection($this->connection)->hasColumn('tim_kerja', 'v_struktur_organisasi_id')) {
                $table->dropIndex(['v_struktur_organisasi_id']);
                $table->dropColumn('v_struktur_organisasi_id');
            }
        });
    }
};
