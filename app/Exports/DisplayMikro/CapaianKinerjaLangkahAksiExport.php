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

class CapaianKinerjaLangkahAksiExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings
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
                $sheet->setCellValue('H1', 'Sub Kegiatan');

                $sheet->mergeCells('I1:I2');
                $sheet->setCellValue('I1', 'Langkah Aksi');

                $sheet->mergeCells('J1:J2');
                $sheet->setCellValue('J1', 'Indikator Kinerja');

                $sheet->mergeCells('K1:K2');
                $sheet->setCellValue('K1', 'Target');

                $sheet->mergeCells('L1:W1');
                $sheet->setCellValue('L1', 'Realisasi Bulan Ke');
                $sheet->setCellValue('L2', '1');
                $sheet->setCellValue('M2', '2');
                $sheet->setCellValue('N2', '3');
                $sheet->setCellValue('O2', '4');
                $sheet->setCellValue('P2', '5');
                $sheet->setCellValue('Q2', '6');
                $sheet->setCellValue('R2', '7');
                $sheet->setCellValue('S2', '8');
                $sheet->setCellValue('T2', '9');
                $sheet->setCellValue('U2', '10');
                $sheet->setCellValue('V2', '11');
                $sheet->setCellValue('W2', '12');

                $sheet->mergeCells('X1:X2');
                $sheet->setCellValue('X1', 'Capaian');

                $sheet->mergeCells('Y1:AA1');
                $sheet->setCellValue('Y1', 'Anggaran');
                $sheet->setCellValue('Y2', 'Target (Rp)');
                $sheet->setCellValue('Z2', 'Realisasi (Rp)');
                $sheet->setCellValue('AA2', 'Capaian (%)');

                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $cellRange = 'A1:Z1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        $headings = [];

        for ($i = 1; $i <= 27; $i++) {
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
                $item->kinerjaKegiatan->kegiatan->nama ?? '-',
                $item->kinerjaSubKegiatan->subKegiatan->nama ?? '-',
                $item->langkah_aksi ?? '-',
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
