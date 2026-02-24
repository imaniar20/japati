<?php

namespace App\Traits;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SasaranStrategisPd;

trait DeleteSKPRelation
{
    protected static function booted(): void
    {
        static::deleted(fn (SasaranStrategisPd|KinerjaProgram|KinerjaKegiatan|KinerjaSubKegiatan $model) => $model->skp()->delete());
    }
}
