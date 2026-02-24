<?php

namespace App\Models\LKE;

use App\Traits\HiddenFields;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HiddenFields;

    protected $table = 'lke_kriteria';

    protected $fillable = [
        'sub_komponen_id',
        'nama',
        'bobot',
        'is_auto',
        'is_locked',
        'jumlah_eviden',
        'keterangan_eviden',
        'nomor',
    ];

    protected $casts = [
        'keterangan_eviden' => 'array',
    ];

    public function subKomponen()
    {
        return $this->belongsTo(SubKomponen::class, 'sub_komponen_id');
    }

    public function parameter()
    {
        return $this->hasMany(Parameter::class, 'kriteria_id');
    }

    public function eviden()
    {
        return $this->hasOne(Eviden::class, 'kriteria_id');
    }
}
