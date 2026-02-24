<?php

namespace App\Models;

use App\Traits\HiddenFields;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class KinerjaLangkahAksi extends Model
{
    use HiddenFields, ScopeRole, ScopeTahun;

    protected $table = 'kinerja_langkah_aksi';

    protected $guarded = [];

    protected $casts = [
        'target_bulanan' => 'json',
        'anggaran_bulanan' => 'json',
        'realisasi_bulanan' => 'json',
        'realisasi_anggaran_bulanan' => 'json',
        'anggaran' => 'float',
        'realisasi_anggaran' => 'float',
    ];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }

    public function sasaranStrategisRpjmd()
    {
        return $this->belongsTo(SasaranStrategisRpjmd::class, 'sasaran_strategis_rpjmd_id');
    }

    public function sasaranStrategisPd()
    {
        return $this->belongsTo(SasaranStrategisPd::class, 'sasaran_strategis_pd_id');
    }

    public function kinerjaProgram()
    {
        return $this->belongsTo(KinerjaProgram::class, 'kinerja_program_id');
    }

    public function kinerjaKegiatan()
    {
        return $this->belongsTo(KinerjaKegiatan::class, 'kinerja_kegiatan_id');
    }

    public function kinerjaSubKegiatan()
    {
        return $this->belongsTo(KinerjaSubKegiatan::class, 'kinerja_sub_kegiatan_id');
    }

    public function kinerjaTidakTercapai()
    {
        return $this->morphMany(KinerjaTidakTercapai::class, 'notable');
    }

    public function solusi()
    {
        return $this->morphOne(SolusiKinerja::class, 'solusi', 'solusi_type', 'solusi_id');
    }
}
