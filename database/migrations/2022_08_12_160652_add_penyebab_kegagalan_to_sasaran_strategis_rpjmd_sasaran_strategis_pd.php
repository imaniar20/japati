<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            foreach ($this->columns() as $column) {
                $table->text($column)->nullable();
            }
        });
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            foreach ($this->columns() as $column) {
                $table->text($column)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sasaran_strategis_rpjmd', function (Blueprint $table) {
            foreach ($this->columns() as $column) {
                $table->dropColumn($column);
            }
        });
        Schema::table('sasaran_strategis_pd', function (Blueprint $table) {
            foreach ($this->columns() as $column) {
                $table->dropColumn($column);
            }
        });
    }

    private function columns(): array
    {
        $columns = ['penyebab_kegagalan_baseline'];

        for ($i = 1; $i <= 5; $i++) {
            $columns[] = "penyebab_kegagalan_{$i}";
        }

        return $columns;
    }
};
