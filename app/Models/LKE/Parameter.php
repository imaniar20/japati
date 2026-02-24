<?php

namespace App\Models\LKE;

use App\Traits\HiddenFields;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HiddenFields;

    protected $table = 'lke_parameter';

    protected $fillable = [
        'kriteria_id',
        'nama',
        'skor',
        'keterangan',
        'nomor',
    ];
}
