<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class IndikatorSasaranStrategis extends Model
{
    use ScopeTahun;

    protected $table = 'indikator_sasaran_strategis';

    protected $guarded = [];

    public function sasaranStrategisRpjmd()
    {
        return $this->hasMany(SasaranStrategisRpjmd::class, 'indikator_sasaran_strategis_id');
    }

    public function sasaranStrategisPd()
    {
        return $this->hasMany(SasaranStrategisPd::class, 'indikator_sasaran_strategis_id');
    }
}
