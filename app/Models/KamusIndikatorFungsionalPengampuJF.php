<?php

namespace App\Models;

use App\Models\Simpeg\Jabatan;
use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusIndikatorFungsionalPengampuJF extends Model
{
    use HasFactory, ScopeRole, ScopeTahun;

    protected $table = 'kamus_indikator_fungsional_pengampu_jf';

    protected $guarded = [];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'jabatan_id');
    }
}
