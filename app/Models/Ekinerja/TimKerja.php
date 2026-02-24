<?php

namespace App\Models\Ekinerja;

use App\Traits\ScopeRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimKerja extends Model
{
    use HasFactory, ScopeRole, SoftDeletes;

    protected $connection = 'ekinerja';

    protected $table = 'tim_kerja';

    protected $fillable = [
        'nama',
        'satuan_kerja_id',
        'v_struktur_organisasi_id',
        'nip_ketua',
    ];

    public function ketua()
    {
        return $this->belongsTo(VPegawaiData::class, 'nip_ketua', 'peg_nip');
    }
}
