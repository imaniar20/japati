<?php

namespace Database\Seeders;

use App\Models\LKE\Eviden;
use App\Models\LKE\Komponen;
use App\Models\LKE\Kriteria;
use App\Models\LKE\Parameter;
use App\Models\LKE\SubKomponen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DuplicateLKEKomponenSeeder extends Seeder
{
    private int $currentTahun = 2024;

    private int $targetTahun = 2025;

    private array $komponenMapping = [];

    private array $subKomponenMapping = [];

    private array $kriteriaMapping = [];

    private array $parameterMapping = [];

    public function run(): void
    {
        if (Komponen::query()->where('tahun_kinerja', $this->targetTahun)->exists()) {
            $this->command->info("Data LKE Komponen {$this->targetTahun} sudah ada, tidak perlu di-generate ulang.");

            return;
        }

        DB::transaction(function () {
            $this->komponen();
            $this->subKomponen();
            $this->kriteria();
            $this->parameter();
            $this->eviden();
        });
    }

    private function komponen()
    {
        $data = Komponen::tahunKinerja($this->currentTahun)->get();

        foreach ($data as $old) {
            $new = $old->replicate()
                ->fill([
                    'tahun_kinerja' => $this->targetTahun,
                ])
                ->makeVisible(get_class($old)::getHiddenFields())
                ->toArray();

            $new = Komponen::create($new);

            $this->komponenMapping[$old->id] = $new->id;
        }
    }

    private function subKomponen()
    {
        $data = SubKomponen::query()
            ->whereIn('komponen_id', array_keys($this->komponenMapping))
            ->get();

        foreach ($data as $old) {
            $new = $old->replicate()
                ->fill([
                    'komponen_id' => $this->komponenMapping[$old->komponen_id],
                ])
                ->makeVisible(get_class($old)::getHiddenFields())
                ->toArray();

            $new = SubKomponen::create($new);

            $this->subKomponenMapping[$old->id] = $new->id;
        }
    }

    private function kriteria()
    {
        $data = Kriteria::query()
            ->whereIn('sub_komponen_id', array_keys($this->subKomponenMapping))
            ->get();

        foreach ($data as $old) {
            $new = $old->replicate()
                ->fill([
                    'sub_komponen_id' => $this->subKomponenMapping[$old->sub_komponen_id],
                ])
                ->makeVisible(get_class($old)::getHiddenFields())
                ->toArray();

            $new = Kriteria::create($new);

            $this->kriteriaMapping[$old->id] = $new->id;
        }
    }

    private function parameter()
    {
        $data = Parameter::query()
            ->whereIn('kriteria_id', array_keys($this->kriteriaMapping))
            ->get();

        foreach ($data as $old) {
            $new = $old->replicate()
                ->fill([
                    'kriteria_id' => $this->kriteriaMapping[$old->kriteria_id],
                ])
                ->makeVisible(get_class($old)::getHiddenFields())
                ->toArray();

            $new = Parameter::create($new);

            $this->parameterMapping[$old->id] = $new->id;
        }
    }

    private function eviden()
    {
        $data = Eviden::tahunKinerja($this->currentTahun)->get();

        foreach ($data as $old) {
            $new = $old->replicate()
                ->fill([
                    'tahun_kinerja' => $this->targetTahun,
                    'kriteria_id' => $this->kriteriaMapping[$old->kriteria_id],
                    'parameter_id' => $old->parameter_id
                        ? $this->parameterMapping[$old->parameter_id]
                        : $old->parameter_id,
                ])
                ->toArray();

            Eviden::create($new);
        }
    }
}
