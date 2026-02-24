<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class AnggaranCapaianIku extends Model
{
    use ScopeTahun;

    protected $table = 'anggaran_capaian_iku';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'terpakai' => 'float',
            'tidak_terpakai' => 'float',
            'efisiensi' => 'float',
        ];
    }
}
