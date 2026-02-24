<?php

namespace App\Imports\LKE;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MainImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Komponen' => new KomponenImport,
            'Sub Komponen' => new SubKomponenImport,
            'Kriteria' => new KriteriaImport,
            'Parameter' => new ParameterImport,
        ];
    }
}
