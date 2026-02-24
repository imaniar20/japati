<?php

namespace App\Models\Simpeg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VPegawaiData extends Model
{
    use HasFactory;

    protected $connection = 'simpeg';

    protected $table = 'v_pegawai_data';

    protected $primaryKey = 'peg_id';

    // public function jf()
    // {
    //     return $this->belongsTo(JF::class, 'jf_id', 'jf_id');
    // }
}
