<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatEviden extends Model
{
    use HasFactory;

    protected $table = 'lke_riwayat_eviden';

    protected $fillable = [
        'penilaian_id',
        'eviden_id',
        'parameter_id',
        'eviden',
        'nilai',
        'catatan',
    ];

    protected $casts = [
        'eviden' => 'array',
    ];

    public function riwayat()
    {
        return $this->belongsTo(Eviden::class, 'eviden_id');
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'parameter_id');
    }

    public function parameterNilai()
    {
        return $this->belongsTo(Parameter::class, 'nilai');
    }

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }

    public function riwayatPenilaianSebelumnya()
    {
        return $this->hasMany(RiwayatEviden::class, 'id', 'id')
            ->whereHas('penilaian', function ($query) {
                $query->whereIn('status', [Penilaian::STATUS_DONE]);
            })
            ->orderBy('id');
    }
}
