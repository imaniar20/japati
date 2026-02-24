<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SasaranStrategisPdCross extends Model
{
    protected $table = 'sasaran_strategis_pd_cross';

    protected $guarded = [];

    public $timestamps = false;

    public function sasaranStrategisRpjmd()
    {
        return $this->belongsTo(SasaranStrategisRpjmd::class, 'sasaran_strategis_rpjmd_id');
    }

    public function sasaranStrategisPd()
    {
        return $this->belongsTo(SasaranStrategisPd::class, 'sasaran_strategis_pd_id');
    }
}
