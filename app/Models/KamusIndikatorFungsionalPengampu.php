<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusIndikatorFungsionalPengampu extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'kamus_indikator_fungsional_pengampu';

    protected $guarded = [];

    public function jf()
    {
        return $this->hasMany(KamusIndikatorFungsionalPengampuJF::class, 'pengampu_id');
    }

    public function sasaran()
    {
        return $this->hasMany(KamusIndikatorFungsionalSasaran::class, 'pengampu_id');
    }
}
