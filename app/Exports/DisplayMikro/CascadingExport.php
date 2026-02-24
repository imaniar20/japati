<?php

namespace App\Exports\DisplayMikro;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CascadingExport implements FromView, ShouldAutoSize
{
    public function __construct(protected Collection $data)
    {
        //
    }

    public function view(): View
    {
        return view('cascading.cascading', [
            'data' => $this->data,
        ]);
    }

    // public function startCell(): string
    // {
    //     return 'A2';
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             /** @var Sheet $sheet */
    //             $sheet = $event->sheet;

    //             $sheet->setCellValue('A1', 'No');

    //             $sheet->setCellValue('B1', 'Tujuan PD');

    //             $sheet->setCellValue('C1', 'Indikator Tujuan PD');

    //             $sheet->setCellValue('D1', 'Target 2025');

    //             $sheet->setCellValue('E1', 'Sasaran PD');

    //             $sheet->setCellValue('F1', 'Indikator Sasaran PD');

    //             $sheet->setCellValue('G1', 'Satuan');

    //             $sheet->setCellValue('H1', 'Target 2025');

    //             $sheet->setCellValue('I1', 'Program');

    //             $sheet->setCellValue('J1', 'Kegiatan');

    //             $sheet->setCellValue('K1', 'Sub Kegiatan');

    //             $sheet->mergeCells('L1:M1');
    //             $sheet->setCellValue('L1', 'Indikator Kinerja Program/Kegiatan/subKegiatan');

    //             $sheet->setCellValue('N1', 'Satuan');

    //             $sheet->setCellValue('O1', 'Target 2025');

    //             $sheet->setCellValue('P1', 'Keterangan');

    //             $styleArray = [
    //                 'alignment' => [
    //                     'horizontal' => Alignment::HORIZONTAL_CENTER,
    //                 ],
    //             ];
    //             $cellRange = 'A1:P1'; // All headers
    //             $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);

    //             $lastColumn = $event->sheet->getHighestColumn();
    //             $lastRow = $event->sheet->getHighestRow();
    //             $styleArrayall = [
    //                 'borders' => [
    //                     'allBorders' => [
    //                         'borderStyle' => Border::BORDER_THIN,
    //                         'color' => ['argb' => '000000'],
    //                     ],
    //                 ],
    //             ];
    //             $cellRangeall = 'A1:' . $lastColumn . $lastRow; // All headers
    //             $event->sheet->getDelegate()->getStyle($cellRangeall)->applyFromArray($styleArrayall);
    //         },
    //     ];
    // }

    // // public function headings(): array
    // // {
    // //     $headings = [];

    // //     for ($i = 1; $i <= 27; $i++) {
    // //         $headings[] = $i;
    // //     }

    // //     return $headings;
    // // }

    // public function collection(): Collection
    // {

    //     $this->data->transform(function ($item, $index){
    //         $sasaran = [];
    //         foreach($item->sasaranStrategisPd as $spd){
    //             // Log::info($spd);
    //             $sasaran[] = [
    //                             $index + 1,
    //                             $item->tujuan->tujuan,
    //                             $item->indikatorSasaranStrategis->indikator ?? '-',
    //                             $item->target ?? '-',
    //                             $spd->sasaran_strategis_satker ?? '-',
    //                             $spd->iku ?? '-',
    //                             $spd->satuan ?? '-',
    //                             $spd->target ?? '-',
    //                             '',
    //                             '',
    //                             '',
    //                             '',

    //                             '',
    //                             '',
    //                             '',
    //                             ''
    //                         ];
    //                         $index++;
    //             foreach($spd->kinerjaProgram as $ikp => $kp){
    //                 $sasaran[] = [
    //                             $index + 1,
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             $kp->program->nama ?? '-',
    //                             '',
    //                             '',
    //                             $ikp+1,

    //                             $kp->indikator ?? '-',
    //                             $kp->satuan ?? '-',
    //                             $kp->target ?? '-',
    //                             ''
    //                         ];
    //                         $index++;
    //                 foreach($kp->kinerjaKegiatan as $ikk => $kk){
    //                     $sasaran[] = [
    //                             $index + 1,
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             $kk->kegiatan->nama ?? '-',
    //                             '',
    //                             $ikk+1,

    //                             $kk->indikator ?? '-',
    //                             $kk->satuan ?? '-',
    //                             $kk->target ?? '-',
    //                             ''
    //                         ];
    //                         $index++;
    //                     foreach($kk->kinerjaSubKegiatan as $iksk => $ksk){
    //                         $sasaran[] = [
    //                             $index + 1,
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             '',
    //                             $ksk->subKegiatan->nama ?? '-',
    //                             $iksk+1,

    //                             $ksk->indikator ?? '-',
    //                             $ksk->satuan ?? '-',
    //                             $ksk->target ?? '-',
    //                             ''
    //                         ];
    //                         $index++;
    //                     }
    //                 }
    //             }
    //         }
    //         return $sasaran;
    //     });

    //     return $this->data;
    // }
}
