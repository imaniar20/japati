<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KinerjaSubKegiatanCross extends Model
{
    protected $table = 'kinerja_sub_kegiatan_cross';

    protected $guarded = [];

    public $timestamps = false;

    public function kinerjaKegiatan()
    {
        return $this->belongsTo(KinerjaKegiatan::class, 'kinerja_kegiatan_id');
    }

    public function kinerjaSubKegiatan()
    {
        return $this->belongsTo(KinerjaSubKegiatan::class, 'kinerja_sub_kegiatan_id');
    }
}
