<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KinerjaTidakTercapai extends Model
{
    use HasFactory, ScopeTahun;

    protected $table = 'kinerja_tidak_tercapai';

    protected $guarded = [];

    public function notable()
    {
        return $this->morphTo();
    }
}
