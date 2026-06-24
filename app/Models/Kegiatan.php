<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use ScopeTahun;

    protected $table = 'kegiatan';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'anggaran' => 'float',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function subKegiatan()
    {
        return $this->hasMany(SubKegiatan::class, 'kegiatan_id');
    }

    public function kinerjaKegiatan()
    {
        return $this->hasMany(KinerjaKegiatan::class, 'kegiatan_id');
    }
}
