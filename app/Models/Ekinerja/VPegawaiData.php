<?php

namespace App\Models\Ekinerja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VPegawaiData extends Model
{
    use HasFactory;

    protected $connection = 'ekinerja';

    protected $table = 'v_pegawai_data';

    public function scopeAktif($query)
    {
        return $query->where('peg_status', true);
    }
}
