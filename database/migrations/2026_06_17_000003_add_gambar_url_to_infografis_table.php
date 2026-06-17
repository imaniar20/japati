<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('infografis', 'gambar_url')) {
            Schema::table('infografis', function (Blueprint $table) {
                $table->text('gambar_url')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('infografis', 'gambar_url')) {
            Schema::table('infografis', function (Blueprint $table) {
                $table->dropColumn('gambar_url');
            });
        }
    }
};
