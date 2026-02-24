<?php

namespace App\Exports\LKE;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenilaianExport implements FromCollection, WithHeadings
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function headings(): array
    {
        return [
            'No',
            'Komponen/Sub Komponen/Kriteria',
            'Jawaban',
            'Penilaian',
            'Eviden',
            'Terima/Tolak',
            'Catatan',
        ];
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->data as $komponen) {
            $data->push([
                "{$komponen->nomor} ",
                $komponen->nama,
                null,
                null,
                null,
                null,
                null,
            ]);

            foreach ($komponen->subKomponen as $subKomponen) {
                $data->push([
                    "{$komponen->nomor}.{$subKomponen->nomor} ",
                    $subKomponen->nama,
                    null,
                    null,
                    null,
                    null,
                    null,
                ]);

                foreach ($subKomponen->kriteria as $kriteria) {
                    $lastRiwayat = $this->getLastRiwayat($kriteria->eviden->riwayat);
                    $parameterPenilaian = $kriteria->parameter->first(fn ($_) => $_->id == $lastRiwayat->nilai);

                    $evidenString = (collect($lastRiwayat->eviden))
                        ->map(function ($eviden, $indexEviden) {
                            $indexEviden++;

                            return "{$indexEviden}. {$eviden}";
                        })
                        ->join("\n");

                    $data->push([
                        "{$kriteria->nomor_full}",
                        $kriteria->nama,
                        "{$kriteria->eviden->riwayat[0]->parameter->nama}\n{$kriteria->eviden->riwayat[0]->parameter->keterangan}",
                        "{$parameterPenilaian?->nama}\n{$parameterPenilaian?->keterangan}",
                        $evidenString,
                        $lastRiwayat->status ? 'Diterima' : 'Ditolak',
                        $lastRiwayat->catatan,
                    ]);
                }
            }
        }

        return $data;
    }

    private function getLastRiwayat(Collection $riwayat)
    {
        return $riwayat[count($riwayat) - 1];
    }
}
