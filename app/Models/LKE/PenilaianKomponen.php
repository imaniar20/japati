<?php

namespace App\Models\LKE;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class PenilaianKomponen extends Model
{
    use ScopeRole, ScopeTahun;

    protected $table = 'lke_penilaian_komponen';

    protected $guarded = [];

    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'komponen_id');
    }
}
