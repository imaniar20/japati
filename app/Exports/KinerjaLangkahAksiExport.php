<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KinerjaLangkahAksiExport implements FromCollection, WithHeadings
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
            'Sub Kegiatan',
            'Sasaran Langkah Aksi',
            'Langkah Aksi',
            'Indikator Langkah Aksi',
            'Satuan',
            'Target Kinerja',
            'Realisasi Kinerja',
        ];
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item, $index) {
            $target = '';
            $realisasi = '';

            foreach (MONTHS as $month) {
                $target .= ucfirst($month[0]).': '.($item->target_bulanan[$month[0]] ?? '-')."\n";
                $realisasi .= ucfirst($month[0]).': '.($item->realisasi_bulanan[$month[0]] ?? '-')."\n";
            }

            return [
                $index + 1,
                $item->satuanKerja->satuan_kerja_nama,
                $item->subKegiatan->nama ?? '-',
                $item->sasaran,
                $item->langkah_aksi,
                $item->indikator,
                $item->satuan,
                $target,
                $realisasi,
            ];
        });

        return $this->data;
    }
}
