<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KinerjaProgramCross extends Model
{
    protected $table = 'kinerja_program_cross';

    protected $guarded = [];

    public $timestamps = false;

    public function sasaranStrategisPd()
    {
        return $this->belongsTo(SasaranStrategisPd::class, 'sasaran_strategis_pd_id');
    }

    public function kinerjaProgram()
    {
        return $this->belongsTo(KinerjaProgram::class, 'kinerja_program_id');
    }
}
