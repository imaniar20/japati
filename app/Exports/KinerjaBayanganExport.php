<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KinerjaBayanganExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function headings(): array
    {
        return [
            'No',
            'Satuan Kerja',
            'Sasaran',
            'Indikator',
            'Target Kinerja',
            'Realisasi Kinerja',
        ];
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item, $index) {
            $target = '';
            $realisasi = '';

            for ($i = 0; $i < 5; $i++) {
                $target .= $item->tahun_mulai + $i.': '.$item['target_'.$i + 1]."\n";
                $realisasi .= $item->tahun_mulai + $i.': '.($item['realisasi_'.$i + 1] ?? '-')."\n";
            }

            return [
                $index + 1,
                $item->satuanKerja->satuan_kerja_nama,
                $item->sasaran,
                $item->indikator,
                $target,
                $realisasi,
            ];
        });

        return $this->data;
    }
}
