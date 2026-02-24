<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KinerjaSubKegiatanExport implements FromCollection, WithHeadings
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
            'Kegiatan',
            'Sasaran Sub Kegiatan',
            'Sub Kegiatan',
            'Indikator Sub Kegiatan',
            'Satuan',
            'Target Kinerja',
            'Realisasi Kinerja',
        ];
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item, $index) {
            return [
                $index + 1,
                $item->satuanKerja->satuan_kerja_nama,
                $item->kegiatan->nama ?? '-',
                $item->sasaran,
                $item->subKegiatan->nama ?? '-',
                $item->indikator,
                $item->satuan,
                $item->target,
                $item->realisasi,
            ];
        });

        return $this->data;
    }
}
