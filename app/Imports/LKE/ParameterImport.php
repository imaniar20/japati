<?php

namespace App\Imports\LKE;

use App\Models\LKE\Parameter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParameterImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (! ($row['id'] ?? null)) {
                continue;
            }

            $model = new Parameter;
            $model->id = $row['id'];
            $model->kriteria_id = $row['kriteria_id'];
            $model->nama = $row['parameter'];
            $model->skor = $row['skor'];
            $model->keterangan = $row['keterangan'];
            $model->save();
        }
    }
}
