<?php

namespace App\Models;

use App\Traits\DeleteSKPRelation;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

/**
 * Khusus Sasaran Strategis PD di Sekretariat Daerah,
 * yang bisa olah data adalah akun `setda`, akun `biro` view-only,
 * `satuan_kerja_id` nya juga di set ke `setda`
 */
class SasaranStrategisPd extends Model
{
    use DeleteSKPRelation, ScopeRole, ScopeTahun;

    const PATH_DEFINISI_OPERASIONAL = 'definisi-operasional';

    protected $table = 'sasaran_strategis_pd';

    protected $guarded = [];

    protected $casts = [
        'target_baseline' => 'float',
        'target_1' => 'float',
        'target_2' => 'float',
        'target_3' => 'float',
        'target_4' => 'float',
        'target_5' => 'float',
        'realisasi_baseline' => 'float',
        'realisasi_1' => 'float',
        'realisasi_2' => 'float',
        'realisasi_3' => 'float',
        'realisasi_4' => 'float',
        'realisasi_5' => 'float',
        'rata_nasional' => 'float',
        'capaian_baseline' => 'float',
        'capaian_1' => 'float',
        'capaian_2' => 'float',
        'capaian_3' => 'float',
        'capaian_4' => 'float',
        'capaian_5' => 'float',
        'lampiran' => 'array',
        'target_baseline_triwulan' => 'json',
        'target_1_triwulan' => 'json',
        'target_2_triwulan' => 'json',
        'target_3_triwulan' => 'json',
        'target_4_triwulan' => 'json',
        'target_5_triwulan' => 'json',
        'realisasi_baseline_triwulan' => 'json',
        'realisasi_1_triwulan' => 'json',
        'realisasi_2_triwulan' => 'json',
        'realisasi_3_triwulan' => 'json',
        'realisasi_4_triwulan' => 'json',
        'realisasi_5_triwulan' => 'json',
        'capaian_baseline_triwulan' => 'json',
        'capaian_1_triwulan' => 'json',
        'capaian_2_triwulan' => 'json',
        'capaian_3_triwulan' => 'json',
        'capaian_4_triwulan' => 'json',
        'capaian_5_triwulan' => 'json',
        'validasi_tahunan' => 'json',
        'validasi_pengampu' => 'json',
    ];

    const PATH_LAMPIRAN = 'sasaran-strategis-pd';

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

    public function kinerjaProgram()
    {
        return $this->hasMany(KinerjaProgram::class, 'sasaran_strategis_pd_id');
    }

    public function sasaranStrategisRpjmd()
    {
        return $this->belongsTo(SasaranStrategisRpjmd::class, 'sasaran_strategis_rpjmd_id');
    }

    public function sasaranStrategisPdCross()
    {
        return $this->hasMany(SasaranStrategisPdCross::class, 'sasaran_strategis_pd_id');
    }

    public function kinerjaProgramCross()
    {
        return $this->hasMany(KinerjaProgramCross::class, 'sasaran_strategis_pd_id');
    }

    public function kinerjaTidakTercapai()
    {
        return $this->morphMany(KinerjaTidakTercapai::class, 'notable');
    }

    public function kinerjaBayangan()
    {
        return $this->belongsTo(KinerjaBayangan::class, 'kinerja_bayangan_id');
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
}
