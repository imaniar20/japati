<?php

namespace App\Models;

use App\Models\Ekinerja\TimKerja;
use App\Traits\DeleteSKPRelation;
use App\Traits\HiddenFields;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KinerjaProgram extends Model
{
    use DeleteSKPRelation, HiddenFields, ScopeRole, ScopeTahun;

    const PATH_DEFINISI_OPERASIONAL = 'definisi-operasional';

    protected $table = 'kinerja_program';

    protected $guarded = [];

    protected $casts = [
        'target_bulanan' => 'json',
        'realisasi_bulanan' => 'json',
        'anggaran_bulanan' => 'json',
        'realisasi_anggaran_bulanan' => 'json',
        'anggaran' => 'float',
        'realisasi_anggaran' => 'float',
        'validasi_cascading' => 'json',
        'validasi_pengampu' => 'json',
    ];

    public function scopeTargetBulanan(Builder $query, string $bulan)
    {
        $column = "(target_bulanan->>'{$bulan}')";

        return $query->whereRaw("{$column} <> '0' AND {$column} <> '' AND {$column} IS NOT NULL");
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function sasaranStrategisPd()
    {
        return $this->belongsTo(SasaranStrategisPd::class, 'sasaran_strategis_pd_id');
    }

    public function satkerIku()
    {
        return $this->belongsTo(SasaranStrategisPd::class, 'satker_iku_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function kinerjaKegiatan()
    {
        return $this->hasMany(KinerjaKegiatan::class, 'kinerja_program_id');
    }

    public function kinerjaProgramCross()
    {
        return $this->hasMany(KinerjaProgramCross::class, 'kinerja_program_id');
    }

    public function kinerjaKegiatanCross()
    {
        return $this->hasMany(KinerjaKegiatanCross::class, 'kinerja_program_id');
    }

    public function strukturOrganisasi()
    {
        return $this->belongsTo(VStrukturOrganisasi::class, 'v_struktur_organisasi_id');
    }

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id');
    }

    public function kinerjaTidakTercapai()
    {
        return $this->morphMany(KinerjaTidakTercapai::class, 'notable');
    }

    public function solusi()
    {
        return $this->morphOne(SolusiKinerja::class, 'solusi', 'solusi_type', 'solusi_id');
    }

    public function kinerjaBayangan()
    {
        return $this->belongsTo(KinerjaBayangan::class, 'kinerja_bayangan_id');
    }

    public function kinerjaTercapai()
    {
        return $this->morphMany(KinerjaTercapai::class, 'notable');
    }

    public function skp()
    {
        return $this->morphMany(SKP::class, 'skp', 'model_class', 'model_id');
    }

    public function riwayatValidasiSkp()
    {
        return $this->morphMany(RiwayatValidasiSKP::class, 'riwayatValidasiSkp', 'model_class', 'model_id');
    }

    public function riwayatSkpRejectedLatest()
    {
        return $this->morphOne(RiwayatValidasiSKP::class, 'riwayatValidasiSkp', 'model_class', 'model_id')->rejected()->latest();
    }

    public function riwayatSkpValidatedLatest()
    {
        return $this->morphOne(RiwayatValidasiSKP::class, 'riwayatValidasiSkp', 'model_class', 'model_id')->validated()->latest();
    }

    public function kamusIndikatorFungsional()
    {
        return $this->morphMany(KamusIndikatorFungsional::class, 'kamusIndikatorFungsional', 'model_class', 'model_id');
    }
}
