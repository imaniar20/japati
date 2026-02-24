<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusIndikatorFungsionalManual extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'kamus_indikator_fungsional_manual';

    protected $guarded = [];

    public function sasaran()
    {
        return $this->belongsTo(KamusIndikatorFungsionalSasaran::class, 'sasaran_id');
    }
}
