<?php

namespace App\Models;

use App\Models\Ekinerja\TimKerja;
use App\Traits\HiddenFields;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use App\Traits\ScopeTargetBulanan;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KinerjaSubKegiatanKabKota extends Model
{
    use HiddenFields, ScopeRole, ScopeTahun, ScopeTargetBulanan;

    protected $table = 'kinerja_sub_kegiatan_kab_kota';

    protected $guarded = [];

    protected $casts = [
        'target_bulanan' => 'json',
        'anggaran_bulanan' => 'json',
        'inovasi_lampiran' => 'array',
        'realisasi_bulanan' => 'json',
        'realisasi_anggaran_bulanan' => 'json',
        'eviden_bulanan' => 'json',
        'validasi_bulanan' => 'json',
        'anggaran' => 'float',
        'realisasi_anggaran' => 'float',
        'validasi_pengampu' => 'json',
    ];

    const PATH_LAMPIRAN = 'kinerja-sub-kegiatan';

    const PATH_EVIDEN_BULANAN = 'kinerja_sub_kegiatan_kab_kota/eviden-bulanan';

    public function scopeTargetBulananTriwulan(Builder $query, int $triwulan)
    {
        $triwulanList = TRIWULAN_BULAN[$triwulan];

        return $query->where(function (Builder $query2) use ($triwulanList) {
            foreach ($triwulanList as $bulan) {
                $column = "(target_bulanan->>'{$bulan}')";
                $query2->orWhere(fn (Builder $query3) => $query3->whereRaw("{$column}::FLOAT > 0 AND {$column} IS NOT NULL"));
            }

            return $query2;
        });
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }

    public function sasaranStrategisRpjmd()
    {
        return $this->belongsTo(SasaranStrategisRpjmd::class, 'sasaran_strategis_rpjmd_id');
    }

    public function kinerjaProgram()
    {
        return $this->belongsTo(KinerjaProgram::class, 'kinerja_program_id');
    }

    public function kinerjaKegiatan()
    {
        return $this->belongsTo(KinerjaKegiatan::class, 'kinerja_kegiatan_id');
    }

    public function kinerjaSubKegiatanCross()
    {
        return $this->hasMany(KinerjaSubKegiatanCross::class, 'kinerja_sub_kegiatan_id');
    }

    public function sasaranStrategisPd()
    {
        return $this->belongsTo(SasaranStrategisPd::class, 'sasaran_strategis_pd_id');
    }

    public function strukturOrganisasi()
    {
        return $this->belongsTo(VStrukturOrganisasi::class, 'v_struktur_organisasi_id');
    }

    public function timKerja()
    {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id');
    }

    public function penyebabKegagalan()
    {
        return $this->hasMany(PenyebabKegagalan::class, 'kinerja_sub_kegiatan_id');
    }

    public function kinerjaTidakTercapai()
    {
        return $this->morphMany(KinerjaTidakTercapai::class, 'notable');
    }

    public function solusi()
    {
        return $this->morphOne(SolusiKinerja::class, 'solusi', 'solusi_type', 'solusi_id');
    }

    public function kinerjaLangkahAksi()
    {
        return $this->hasMany(KinerjaLangkahAksi::class, 'kinerja_sub_kegiatan_id');
    }

    public function kinerjaKabKota()
    {
        return $this->belongsTo(KinerjaKabKota::class, 'kab_kota_id', 'id');
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
