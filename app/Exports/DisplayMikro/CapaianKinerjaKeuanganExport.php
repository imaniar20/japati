<?php

namespace App\Exports\DisplayMikro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CapaianKinerjaKeuanganExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:A2');
                $sheet->setCellValue('A1', 'No');

                $sheet->mergeCells('B1:B2');
                $sheet->setCellValue('B1', 'Satuan Kerja');

                $sheet->mergeCells('C1:C2');
                $sheet->setCellValue('C1', 'Sasaran');

                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', 'Indikator');

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', '% Capaian Kinerja');

                $sheet->mergeCells('F1:F2');
                $sheet->setCellValue('F1', 'Program');

                $sheet->mergeCells('G1:G2');
                $sheet->setCellValue('G1', 'Realisasi Anggaran');

                $sheet->mergeCells('H1:H2');
                $sheet->setCellValue('H1', 'Kegiatan');

                $sheet->mergeCells('I1:I2');
                $sheet->setCellValue('I1', 'Realisasi Anggaran');

                $sheet->mergeCells('J1:J2');
                $sheet->setCellValue('J1', 'Sub Kegiatan');

                $sheet->mergeCells('K1:K2');
                $sheet->setCellValue('K1', 'Realisasi Anggaran');

                $sheet->mergeCells('L1:L2');
                $sheet->setCellValue('L1', 'Aktivitas');

                $sheet->mergeCells('M1:M2');
                $sheet->setCellValue('M1', 'Realisasi Anggaran');

                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $cellRange = 'A1:M1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        $headings = [];

        for ($i = 1; $i <= 13; $i++) {
            $headings[] = $i;
        }

        return $headings;
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item, $index) {
            return [
                $index + 1,
                $item->satuanKerja->satuan_kerja_nama,
                $item->sasaranStrategisPd->sasaran_strategis_satker ?? '-',
                $item->sasaranStrategisPd->iku ?? '-',
                $item->sasaranStrategisPd[getKeyTahun('capaian')] ?? '-',
                $item->kinerjaProgram->program->nama ?? '-',
                $item->kinerjaProgram->realisasi_anggaran ?? '-',
                $item->kinerjaKegiatan->kegiatan->nama ?? '-',
                $item->kinerjaKegiatan->realisasi_anggaran ?? '-',
                $item->kinerjaSubKegiatan->subKegiatan->nama ?? '-',
                $item->kinerjaSubKegiatan->realisasi_anggaran ?? '-',
                $item->langkah_aksi ?? '-',
                $item->realisasi_anggaran ?? '-',
            ];
        });

        return $this->data;
    }
}
