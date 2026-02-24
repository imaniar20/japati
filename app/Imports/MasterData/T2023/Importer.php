<?php

namespace App\Imports\MasterData\T2023;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Importer implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new FirstSheet,
        ];
    }
}
