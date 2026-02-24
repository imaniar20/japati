<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lke_komponen', function (Blueprint $table) {
            $table->unsignedInteger('nomor')->default(0);
        });
        Schema::table('lke_sub_komponen', function (Blueprint $table) {
            $table->unsignedInteger('nomor')->default(0);
        });
        Schema::table('lke_kriteria', function (Blueprint $table) {
            $table->unsignedInteger('nomor')->default(0);
        });
        Schema::table('lke_parameter', function (Blueprint $table) {
            $table->unsignedInteger('nomor')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lke_komponen', function (Blueprint $table) {
            $table->dropColumn('nomor');
        });
        Schema::table('lke_sub_komponen', function (Blueprint $table) {
            $table->dropColumn('nomor');
        });
        Schema::table('lke_kriteria', function (Blueprint $table) {
            $table->dropColumn('nomor');
        });
        Schema::table('lke_parameter', function (Blueprint $table) {
            $table->dropColumn('nomor');
        });
    }
};
