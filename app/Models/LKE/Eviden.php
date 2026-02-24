<?php

namespace App\Models\LKE;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eviden extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'lke_eviden';

    protected $fillable = [
        'satuan_kerja_id',
        'tahun_kinerja',
        'kriteria_id',
        'parameter_id',
        'eviden',
    ];

    protected $casts = [
        'eviden' => 'array',
    ];

    const PATH_EVIDEN = 'lke';

    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'parameter_id');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatEviden::class, 'eviden_id');
    }

    public function riwayatPenilaianSebelumnya()
    {
        return $this->hasMany(RiwayatEviden::class, 'eviden_id')
            ->whereHas('penilaian', function ($query) {
                $query->whereIn('status', [Penilaian::STATUS_DONE]);
            })
            ->orderBy('id');
    }
}
