<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyebabKegagalan extends Model
{
    use HasFactory;

    protected $table = 'penyebab_kegagalan';

    protected $fillable = [
        'kinerja_sub_kegiatan_id',
        'triwulan',
        'penyebab',
    ];

    public function kinerjaSubKegiatan()
    {
        return $this->belongsTo(KinerjaSubKegiatan::class, 'kinerja_sub_kegiatan_id');
    }

    public function langkahAksi()
    {
        return $this->hasMany(KinerjaLangkahAksi::class, 'penyebab_kegagalan_id');
    }
}
