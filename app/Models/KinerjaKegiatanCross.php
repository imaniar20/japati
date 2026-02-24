<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KinerjaKegiatanCross extends Model
{
    protected $table = 'kinerja_kegiatan_cross';

    protected $guarded = [];

    public $timestamps = false;

    public function kinerjaProgram()
    {
        return $this->belongsTo(KinerjaProgram::class, 'kinerja_program_id');
    }

    public function kinerjaKegiatan()
    {
        return $this->belongsTo(KinerjaKegiatan::class, 'kinerja_kegiatan_id');
    }
}
