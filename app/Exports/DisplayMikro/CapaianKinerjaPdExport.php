<?php

namespace App\Exports\DisplayMikro;

use App\Models\SasaranStrategisPd;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CapaianKinerjaPdExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings, WithStrictNullComparison
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        $tahunKinerja = getTahunKinerja();

        return [
            AfterSheet::class => function (AfterSheet $event) use ($tahunKinerja) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:A3');
                $sheet->setCellValue('A1', 'No');

                $sheet->mergeCells('B1:B3');
                $sheet->setCellValue('B1', 'Satuan Kerja');

                $sheet->mergeCells('C1:C3');
                $sheet->setCellValue('C1', 'Sasaran');

                $sheet->mergeCells('D1:D3');
                $sheet->setCellValue('D1', 'Indikator');

                $sheet->mergeCells('E1:G1');
                $sheet->setCellValue('E1', 'P1');

                $sheet->mergeCells('E2:G2');
                $sheet->setCellValue('E2', "Capaian Tahun {$tahunKinerja}");
                $sheet->setCellValue('E3', 'Target');
                $sheet->setCellValue('F3', 'Realisasi');
                $sheet->setCellValue('G3', '% Capaian');

                $sheet->mergeCells('H1:M1');
                $sheet->setCellValue('H1', 'P2');

                $sheet->mergeCells('H2:M2');
                $sheet->setCellValue('H2', 'Peningkatan dari Tahun Lalu');
                $sheet->setCellValue('H3', "Realisasi {$tahunKinerja}");
                $sheet->setCellValue('I3', 'Realisasi Tahun lalu');
                $sheet->setCellValue('J3', 'Perbandingan realisasi dari tahun lalu');
                $sheet->setCellValue('K3', "Capaian {$tahunKinerja}");
                $sheet->setCellValue('L3', 'Capaian Tahun Lalu');
                $sheet->setCellValue('M3', 'Peningkatan Capaian dari Tahun Lalu');

                $sheet->mergeCells('N1:O1');
                $sheet->setCellValue('N1', 'P3');
                $sheet->setCellValue('N2', "Capaian Tahun {$tahunKinerja} Terhadap Target Akhir Renstra");
                $sheet->setCellValue('N3', 'Target akhir Renstra');
                $sheet->setCellValue('O3', "Realisasi Tahun {$tahunKinerja} Terhadap Target Akhir Renstra");

                $sheet->mergeCells('P1:Q1');
                $sheet->setCellValue('P1', 'P4');
                $sheet->setCellValue('P2', 'Perbandingan Dengan Nasional');
                $sheet->setCellValue('P3', 'Rata-Rata Nasional');
                $sheet->setCellValue('Q3', 'Peringkat di Tingkat Nasional');

                $sheet->mergeCells('R1:R3');
                $sheet->setCellValue('R1', 'Penghargaan');

                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $cellRange = 'A1:R1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        $headings = [];

        for ($i = 1; $i <= 18; $i++) {
            $headings[] = $i;
        }

        return $headings;
    }

    private function parsePeningkatan(SasaranStrategisPd $item, string $key)
    {
        $sekarang = $item[getKeyTahun($key)];
        $sebelum = $item[getKeyTahun($key, -1)];

        if (! $sekarang || ! $sebelum) {
            return '-';
        }

        return round($sekarang - $sebelum, 2);
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item, $index) {
            return [
                $index + 1,
                $item->satuanKerja->satuan_kerja_nama,
                $item->sasaran_strategis_satker ?? '-',
                $item->iku ?? '-',
                $item[getKeyTahun('target')] ?? '-',
                $item[getKeyTahun('realisasi')] ?? '-',
                $item[getKeyTahun('capaian')] ?? '-',
                $item[getKeyTahun('realisasi')] ?? '-',
                $item[getKeyTahun('realisasi', -1)] ?? '-',
                $this->parsePeningkatan($item, 'realisasi') ?? '-',
                $item[getKeyTahun('capaian')] ?? '-',
                $item[getKeyTahun('capaian', -1)] ?? '-',
                $this->parsePeningkatan($item, 'capaian') ?? '-',
                $item->target_5 ?? '-',
                $item->target_5 ? round($item[getKeyTahun('realisasi')] / $item->target_5, 2) : '-',
                $item->rata_nasional ?? '-',
                $item->peringkat_nasional ?? '-',
                $item->redaksi ?? '-',
            ];
        });

        return $this->data;
    }
}
