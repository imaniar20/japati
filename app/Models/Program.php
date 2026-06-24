<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use ScopeTahun;

    protected $table = 'program';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'anggaran' => 'float',
        ];
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id', 'satuan_kerja_id');
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'program_id');
    }

    public function kinerjaProgram()
    {
        return $this->hasMany(KinerjaProgram::class, 'program_id');
    }

    public function kinerjaKegiatan()
    {
        return $this->hasMany(KinerjaKegiatan::class, 'program_id');
    }
}
