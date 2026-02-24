<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class SasaranStrategis extends Model
{
    use ScopeTahun;

    protected $table = 'sasaran_strategis';

    protected $guarded = [];

    public function sasaranStrategisRpjmd()
    {
        return $this->hasMany(SasaranStrategisRpjmd::class, 'sasaran_strategis_id');
    }
}
