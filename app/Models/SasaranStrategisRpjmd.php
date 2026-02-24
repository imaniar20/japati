<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class SasaranStrategisRpjmd extends Model
{
    use ScopeRole, ScopeTahun;

    protected $table = 'sasaran_strategis_rpjmd';

    protected $guarded = [];

    protected $casts = [
        'rata_nasional' => 'float',
        'capaian_baseline' => 'float',
        'capaian_1' => 'float',
        'capaian_2' => 'float',
        'capaian_3' => 'float',
        'capaian_4' => 'float',
        'capaian_5' => 'float',
        'capaian_terhadap_target_akhir' => 'float',
    ];

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

    public function targetVisiMisi()
    {
        return $this->belongsTo(VisiMisiRpjmd::class, 'target_visi_misi_rpjmd_id')
            ->select('id', 'tahun_mulai', 'target_1', 'target_2', 'target_3', 'target_4', 'target_5');
    }

    public function sasaranStrategisPd()
    {
        return $this->hasMany(SasaranStrategisPd::class, 'sasaran_strategis_rpjmd_id');
    }

    public function sasaranStrategisPdCross()
    {
        return $this->hasMany(SasaranStrategisPdCross::class, 'sasaran_strategis_rpjmd_id');
    }

    public function kinerjaTidakTercapai()
    {
        return $this->morphMany(KinerjaTidakTercapai::class, 'notable');
    }

    public function kinerjaBayangan()
    {
        return $this->hasMany(KinerjaBayangan::class, 'sasaran_strategis_rpjmd_id');
    }
}
