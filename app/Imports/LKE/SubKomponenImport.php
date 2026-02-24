<?php

namespace App\Imports\LKE;

use App\Models\LKE\SubKomponen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubKomponenImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (! ($row['id'] ?? null)) {
                continue;
            }

            $model = new SubKomponen;
            $model->id = $row['id'];
            $model->komponen_id = $row['komponen_id'];
            $model->nama = $row['nama'];
            $model->save();
        }
    }
}
