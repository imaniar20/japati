<?php

namespace App\Models\LKE;

use App\Traits\HiddenFields;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    use HiddenFields, ScopeTahun;

    protected $table = 'lke_komponen';

    protected $fillable = [
        'nama',
        'tahun_kinerja',
        'nomor',
    ];

    public function subKomponen()
    {
        return $this->hasMany(SubKomponen::class, 'komponen_id');
    }
}
