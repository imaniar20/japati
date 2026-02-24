<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LKIPNarasiPD extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'lkip_narasi_pd';

    protected $guarded = [];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function sasaranStrategis()
    {
        return $this->belongsTo(SasaranStrategis::class, 'sasaran_strategis_id');
    }

    public function indikatorSasaranStrategis()
    {
        return $this->belongsTo(IndikatorSasaranStrategis::class, 'indikator_sasaran_strategis_id');
    }
}
