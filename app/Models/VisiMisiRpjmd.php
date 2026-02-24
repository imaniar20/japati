<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class VisiMisiRpjmd extends Model
{
    use ScopeRole, ScopeTahun;

    protected $table = 'visi_misi_rpjmd';

    protected $guarded = [];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function visi()
    {
        return $this->belongsTo(Visi::class, 'visi_id');
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

    public function kinerjaTidakTercapai()
    {
        return $this->morphMany(KinerjaTidakTercapai::class, 'notable');
    }
}
