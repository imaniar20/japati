<?php

namespace App\Exports\LKE;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class HasilPenilaianExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    private array $predikatAkhir;

    public function __construct(
        protected Collection $data,
        protected float $bobotTotal,
        protected float $skorTotal,
        protected float $skorTotal2,
        protected float $skorTotalPenilaianKomponen,
        protected array $predikat,
        protected array $predikat2,
        protected array $predikatKomponen,
        protected bool $done1,
        protected bool $done2,
    ) {
        if ($this->skorTotalPenilaianKomponen) {
            $this->predikatAkhir = $this->predikatKomponen;
        } elseif ($this->done2) {
            $this->predikatAkhir = $this->predikat2;
        } else {
            $this->predikatAkhir = $this->predikat;
        }
    }

    public function headings(): array
    {
        return [
            'No',
            'Komponen/Sub Komponen',
            'Bobot',
            'Nilai Tahap Awal',
            'Nilai Tahap Akhir',
            'Nilai Pleno',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = collect();

        foreach ($this->data as $komponen) {
            $data->push([
                "{$komponen->nomor} ",
                $komponen->nama,
                $komponen->bobot,
                $komponen->skor,
                $komponen->skor2,
                $komponen->skor_penilaian,
            ]);

            foreach ($komponen->subKomponen as $subKomponen) {
                $data->push([
                    "{$komponen->nomor}.{$subKomponen->nomor} ",
                    $subKomponen->nama,
                    $subKomponen->bobot,
                    $subKomponen->skor,
                    $subKomponen->skor2,
                    null,
                ]);
            }
        }

        $data->push([
            'NILAI TOTAL',
            null,
            $this->bobotTotal,
            $this->skorTotal,
            $this->skorTotal2,
            $this->skorTotalPenilaianKomponen,
        ]);

        $predikat0 = $this->predikatAkhir[0];
        $predikat1 = $this->predikatAkhir[1];

        $tahap = '';

        if ($this->skorTotalPenilaianKomponen) {
            // do nothing
        } elseif ($this->done2) {
            $tahap = 'TAHAP AKHIR';
        } else {
            $tahap = 'TAHAP AWAL';
        }

        $data->push([
            "NILAI SAKIP {$tahap}",
            null,
            "{$predikat0}\n{$predikat1}",
            null,
            null,
        ]);

        $data->push([
            $this->predikatAkhir[2],
            null,
            null,
            null,
            null,
        ]);

        return $data;
    }
}
