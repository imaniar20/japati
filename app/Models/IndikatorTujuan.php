<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class IndikatorTujuan extends Model
{
    use ScopeTahun;

    protected $table = 'indikator_tujuan';

    protected $guarded = [];

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'tujuan_id');
    }
}
