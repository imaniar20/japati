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

class RencanaAksiPdExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings
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
                $sheet->setCellValue('C1', 'Sasaran Strategis RPJMD');

                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', 'IKU Gubernur');

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', 'Target');

                $sheet->mergeCells('F1:F2');
                $sheet->setCellValue('F1', 'Sasaran Strategis PD');

                $sheet->mergeCells('G1:G2');
                $sheet->setCellValue('G1', 'Indikator Kinerja');

                $sheet->mergeCells('H1:H2');
                $sheet->setCellValue('H1', 'Program');

                $sheet->mergeCells('I1:I2');
                $sheet->setCellValue('I1', 'Kegiatan');

                $sheet->mergeCells('J1:J2');
                $sheet->setCellValue('J1', 'Sub Kegiatan');

                $sheet->mergeCells('K1:K2');
                $sheet->setCellValue('K1', 'Aktivitas');

                $sheet->mergeCells('L1:L2');
                $sheet->setCellValue('L1', 'Indikator Aktivitas');

                $sheet->mergeCells('M1:X1');
                $sheet->setCellValue('M1', 'Target');
                $sheet->setCellValue('M2', '1');
                $sheet->setCellValue('N2', '2');
                $sheet->setCellValue('O2', '3');
                $sheet->setCellValue('P2', '4');
                $sheet->setCellValue('Q2', '5');
                $sheet->setCellValue('R2', '6');
                $sheet->setCellValue('S2', '7');
                $sheet->setCellValue('T2', '8');
                $sheet->setCellValue('U2', '9');
                $sheet->setCellValue('V2', '10');
                $sheet->setCellValue('W2', '11');
                $sheet->setCellValue('X2', '12');

                $sheet->mergeCells('Y1:Y2');
                $sheet->setCellValue('Y1', 'Jumlah');

                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $cellRange = 'A1:Y1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        $headings = [];

        for ($i = 1; $i <= 25; $i++) {
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
                $item->sasaranStrategisRpjmd->sasaranStrategis->sasaran ?? '-',
                $item->sasaranStrategisRpjmd->indikatorSasaranStrategis->indikator ?? '-',
                $item->sasaranStrategisRpjmd[getKeyTahun('target')] ?? '-',
                $item->sasaranStrategisPd->sasaran_strategis_satker ?? '-',
                $item->sasaranStrategisPd->iku ?? '-',
                $item->kinerjaProgram->program->nama ?? '-',
                $item->kinerjaKegiatan->kegiatan->nama ?? '-',
                $item->kinerjaSubKegiatan->subKegiatan->nama ?? '-',
                $item->langkah_aksi ?? '-',
                $item->indikator ?? '-',
                $item->target_bulanan['jan'],
                $item->target_bulanan['feb'],
                $item->target_bulanan['mar'],
                $item->target_bulanan['apr'],
                $item->target_bulanan['may'],
                $item->target_bulanan['jun'],
                $item->target_bulanan['jul'],
                $item->target_bulanan['aug'],
                $item->target_bulanan['sep'],
                $item->target_bulanan['oct'],
                $item->target_bulanan['nov'],
                $item->target_bulanan['dec'],
                $item->target,
            ];
        });

        return $this->data;
    }
}
