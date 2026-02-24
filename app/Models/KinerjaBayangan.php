<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KinerjaBayangan extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'kinerja_bayangan';

    protected $guarded = [];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function misi()
    {
        return $this->belongsTo(Misi::class, 'misi_id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'tujuan_id');
    }

    public function indikatorTujuan()
    {
        return $this->belongsTo(IndikatorTujuan::class, 'indikator_tujuan_id');
    }

    public function sasaranStrategisPd()
    {
        return $this->hasMany(SasaranStrategisPd::class, 'kinerja_bayangan_id');
    }
}
