<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusIndikatorFungsionalSasaran extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'kamus_indikator_fungsional_sasaran';

    protected $guarded = [];

    public function pengampu()
    {
        return $this->belongsTo(KamusIndikatorFungsionalPengampu::class, 'pengampu_id');
    }
}
