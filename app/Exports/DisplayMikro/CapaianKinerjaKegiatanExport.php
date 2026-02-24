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

class CapaianKinerjaKegiatanExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings
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
                $sheet->setCellValue('G1', 'Kegiatan');

                $sheet->mergeCells('H1:H2');
                $sheet->setCellValue('H1', 'Indikator Kinerja');

                $sheet->mergeCells('I1:I2');
                $sheet->setCellValue('I1', 'Target');

                $sheet->mergeCells('J1:U1');
                $sheet->setCellValue('J1', 'Realisasi Bulan Ke');
                $sheet->setCellValue('J2', '1');
                $sheet->setCellValue('K2', '2');
                $sheet->setCellValue('L2', '3');
                $sheet->setCellValue('M2', '4');
                $sheet->setCellValue('N2', '5');
                $sheet->setCellValue('O2', '6');
                $sheet->setCellValue('P2', '7');
                $sheet->setCellValue('Q2', '8');
                $sheet->setCellValue('R2', '9');
                $sheet->setCellValue('S2', '10');
                $sheet->setCellValue('T2', '11');
                $sheet->setCellValue('U2', '12');

                $sheet->mergeCells('V1:V2');
                $sheet->setCellValue('V1', 'Capaian');

                $sheet->mergeCells('W1:Y1');
                $sheet->setCellValue('W1', 'Anggaran');
                $sheet->setCellValue('W2', 'Target (Rp)');
                $sheet->setCellValue('X2', 'Realisasi (Rp)');
                $sheet->setCellValue('Y2', 'Capaian (%)');

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
                $item->sasaranStrategisPd->sasaran_strategis_satker ?? '-',
                $item->sasaranStrategisPd->iku ?? '-',
                $item->sasaranStrategisPd[getKeyTahun('capaian')] ?? '-',
                $item->kinerjaProgram->Program->nama ?? '-',
                $item->kegiatan->nama ?? '-',
                $item->indikator ?? '-',
                $item->target ?? '-',
                $item->realisasi_bulanan['jan'] ?? '-',
                $item->realisasi_bulanan['feb'] ?? '-',
                $item->realisasi_bulanan['mar'] ?? '-',
                $item->realisasi_bulanan['apr'] ?? '-',
                $item->realisasi_bulanan['may'] ?? '-',
                $item->realisasi_bulanan['jun'] ?? '-',
                $item->realisasi_bulanan['jul'] ?? '-',
                $item->realisasi_bulanan['aug'] ?? '-',
                $item->realisasi_bulanan['sep'] ?? '-',
                $item->realisasi_bulanan['oct'] ?? '-',
                $item->realisasi_bulanan['nov'] ?? '-',
                $item->realisasi_bulanan['dec'] ?? '-',
                $item->capaian ?? '-',
                $item->anggaran ?? '-',
                $item->realisasi_anggaran ?? '-',
                $item->capaian_anggaran ?? '-',
            ];
        });

        return $this->data;
    }
}
