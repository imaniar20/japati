<?php

namespace App\Models\LKE;

use App\Models\SatuanKerja;
use App\Traits\HiddenFields;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HiddenFields, ScopeRole, ScopeTahun;

    protected $table = 'lke_rekomendasi';

    protected $fillable = [
        'rekomendasi',
        'satuan_kerja_id',
        'tahun_kinerja',
        'tindak_lanjut',
        'target',
        'waktu',
        'penanggung_jawab',
        'status',
        'link_eviden',
        'status_monev',
    ];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }
}
