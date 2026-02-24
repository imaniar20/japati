<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SasaranStrategisPDExport implements FromCollection, WithHeadings
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
            'Sasaran Strategis RPJMD',
            'IKU Gubernur',
            'Sasaran Strategis PD',
            'IKU PD',
            'Satuan',
            'Target Kinerja',
            'Realisasi',
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
                $item->sasaranStrategis->sasaran,
                $item->indikatorSasaranStrategis->indikator,
                $item->sasaran_strategis_satker,
                $item->iku,
                $item->satuan,
                $target,
                $realisasi,
            ];
        });

        return $this->data;
    }
}
