<?php

namespace App\Models\Ekinerja;

use App\Models\SKP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SasaranKinerja extends Model
{
    use SoftDeletes;

    protected $connection = 'ekinerja';

    protected $table = 'sasaran_kinerja';

    public function skp()
    {
        return $this->belongsTo(SKP::class, 'sakip_id');
    }
}
