<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RankRaporKinerjaExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function headings(): array
    {
        return [
            'Rank',
            'Satuan Kerja/Biro',
            'Capaian Kinerja',
            'Efisiensi',
            'Jumlah Anggaran',
            'Jumlah yang Terserap',
            'Jumlah yang tidak Terserap',
            '% Terserap',
            '% Tidak Terserap',
        ];
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item) {
            return [
                $item->rank,
                $item->satuan_kerja_nama,
                $item->capaian ?? '-',
                $item->efisiensi_anggaran ?? '-',
                $item->anggaran ?? '-',
                $item->anggaran_terserap ?? '-',
                $item->anggaran_tidak_terserap ?? '-',
                $item->anggaran ? round(($item->anggaran_terserap / $item->anggaran) * 100, 2) : 0,
                $item->anggaran ? round(($item->anggaran_tidak_terserap / $item->anggaran) * 100, 2) : 0,
            ];
        });

        return $this->data;
    }
}
