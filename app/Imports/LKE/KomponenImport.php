<?php

namespace App\Imports\LKE;

use App\Models\LKE\Komponen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KomponenImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (! ($row['id'] ?? null)) {
                continue;
            }

            $model = new Komponen;
            $model->id = $row['id'];
            $model->nama = $row['nama'];
            $model->save();
        }
    }
}
