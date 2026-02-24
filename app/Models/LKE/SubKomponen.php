<?php

namespace App\Models\LKE;

use App\Traits\HiddenFields;
use Illuminate\Database\Eloquent\Model;

class SubKomponen extends Model
{
    use HiddenFields;

    protected $table = 'lke_sub_komponen';

    protected $fillable = [
        'komponen_id',
        'nama',
        'nomor',
    ];

    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'komponen_id');
    }

    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'sub_komponen_id');
    }
}
