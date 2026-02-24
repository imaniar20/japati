<?php

namespace App\Models\LKE;

use App\Models\SatuanKerja;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'lke_penilaian';

    protected $fillable = [
        'satuan_kerja_id',
        'tahun_kinerja',
        'status',
    ];

    /**
     * PD sudah submit validasi tapi belum atau sedang dinilai validasi tahap 1
     */
    const STATUS_IN_ASSESSMENT = 'in-assessment';

    /**
     * Sudah dinilai validasi tahap 1
     */
    const STATUS_DONE = 'done';

    /**
     * PD sudah submit validasi tapi belum atau sedang dinilai validasi tahap 2
     */
    const STATUS_IN_ASSESSMENT_2 = 'in-assessment-2';

    /**
     * Sudah dinilai validasi tahap 2
     */
    const STATUS_DONE_2 = 'done-2';

    const STATUS_HUMANIS_DONE = 'humanis-done';

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function penilaianKomponen()
    {
        return $this->hasMany(PenilaianKomponen::class, 'penilaian_id');
    }
}
