<?php

namespace Database\Seeders;

use App\Models\LKE\Komponen;
use App\Models\LKE\Kriteria;
use App\Models\LKE\Parameter;
use App\Models\LKE\SubKomponen;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateNomorLKESeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Komponen::query()
                ->where('tahun_kinerja', 2023)
                ->with([
                    'subKomponen' => fn (Builder $query) => $query->orderBy('id'),
                    'subKomponen.kriteria' => fn (Builder $query) => $query->orderBy('id'),
                    'subKomponen.kriteria.parameter' => fn (Builder $query) => $query->orderBy('id'),
                ])
                ->get()
                ->each(function (Komponen $komponen, int $komponenIndex) {
                    $komponen->nomor = $komponenIndex + 1;
                    $komponen->save();

                    $komponen->subKomponen->each(function (SubKomponen $subKomponen, int $subKomponenIndex) {
                        $subKomponen->nomor = $subKomponenIndex + 1;
                        $subKomponen->save();

                        $subKomponen->kriteria->each(function (Kriteria $kriteria, int $kriteriaIndex) {
                            $kriteria->nomor = $kriteriaIndex + 1;
                            $kriteria->save();

                            $kriteria->parameter->each(function (Parameter $parameter, int $parameterIndex) {
                                $parameter->nomor = $parameterIndex + 1;
                                $parameter->save();
                            });
                        });
                    });
                });
        });
    }
}
