<?php

namespace App\Imports\LKE;

use App\Models\LKE\Kriteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KriteriaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (! ($row['id'] ?? null)) {
                continue;
            }

            $model = new Kriteria;
            $model->id = $row['id'];
            $model->sub_komponen_id = $row['sub_komponen_id'];
            $model->nama = $row['kriteria'];
            $model->bobot = $row['bobot'];
            $model->is_auto = $row['otomatis'];
            $model->is_locked = $row['is_locked'];
            $model->jumlah_eviden = $row['jumlah_evidance'];
            $model->keterangan_eviden = array_map(fn (string $item) => trim($item), explode('|', $row['evidance']));
            $model->save();
        }
    }
}
