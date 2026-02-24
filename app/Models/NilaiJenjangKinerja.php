<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiJenjangKinerja extends Model
{
    use HasFactory, ScopeTahun;

    protected $table = 'nilai_jenjang_kinerja';

    protected $guarded = [];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id');
    }
}
