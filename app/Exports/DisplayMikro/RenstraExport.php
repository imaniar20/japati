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

class RenstraExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function startCell(): string
    {
        return 'A2';
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

                $sheet->mergeCells('E1:I1');
                $sheet->setCellValue('E1', 'Target Tahun ke-');

                $styleArray = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Satuan Kerja',
            'Sasaran',
            'Indikator',
            '1',
            '2',
            '3',
            '4',
            '5',
        ];
    }

    public function collection(): Collection
    {
        $this->data->transform(function ($item, $index) {
            return [
                $index + 1,
                $item->satuanKerja->satuan_kerja_nama,
                $item->sasaran_strategis_satker,
                $item->iku,
                $item->target_1,
                $item->target_2,
                $item->target_3,
                $item->target_4,
                $item->target_5,

            ];
        });

        return $this->data;
    }
}
