<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use ScopeTahun;

    protected $table = 'sub_kegiatan';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'anggaran' => 'float',
        ];
    }

    public function kinerjaSubKegiatan()
    {
        return $this->hasMany(KinerjaSubKegiatan::class, 'sub_kegiatan_id');
    }

    public function kinerjaSubKegiatanKabKota()
    {
        return $this->hasMany(KinerjaSubKegiatanKabKota::class, 'sub_kegiatan_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function indikator()
    {
        return $this->hasMany(IndikatorSubKegiatan::class, 'sub_kegiatan_id');
    }
}
