<?php

namespace Database\Seeders;

use App\Imports\LKE\MainImport;
use App\Models\LKE\Komponen;
use App\Models\LKE\Kriteria;
use App\Models\LKE\Parameter;
use App\Models\LKE\SubKomponen;
use Exception;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class LKESeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->ensureEmpty();

        Excel::import(new MainImport, database_path('seeders/master-data/2022/lke.xlsx'));
    }

    private function ensureEmpty()
    {
        if (
            Komponen::query()->exists()
            || SubKomponen::query()->exists()
            || Kriteria::query()->exists()
            || Parameter::query()->exists()
        ) {
            throw new Exception('Batal import karena sudah ada data LKE');
        }
    }
}
