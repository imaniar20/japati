<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorSubKegiatan extends Model
{
    use HasFactory;

    protected $table = 'indikator_sub_kegiatan';

    protected $guarded = [];

    public function subKegiatan()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }
}
