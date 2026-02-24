<?php

namespace App\Models\Simpeg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'simpeg';

    protected $table = 'm_spg_jabatan';

    protected $primaryKey = 'jabatan_id';

    public function jf()
    {
        return $this->belongsTo(JF::class, 'jf_id', 'jf_id');
    }
}
