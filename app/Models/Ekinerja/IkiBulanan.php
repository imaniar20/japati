<?php

namespace App\Models\Ekinerja;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IkiBulanan extends Model
{
    use SoftDeletes;

    protected $connection = 'ekinerja';

    protected $table = 'iki_bulanan';

    protected $casts = [
        'target' => 'float',
    ];

    public function sasaranKinerja()
    {
        return $this->belongsTo(SasaranKinerja::class, 'sasaran_kinerja_id');
    }
}
