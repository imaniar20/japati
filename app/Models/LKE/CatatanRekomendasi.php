<?php

namespace App\Models\LKE;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanRekomendasi extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'lke_catatan_rekomendasi';

    protected $fillable = [
        'satuan_kerja_id',
        'tahun_kinerja',
        'user_id',
        'catatan',
        'rekomendasi',
    ];

    protected $casts = [
        'catatan' => 'array',
        'rekomendasi' => 'array',
    ];
}
