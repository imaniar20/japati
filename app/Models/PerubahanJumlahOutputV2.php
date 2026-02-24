<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class PerubahanJumlahOutputV2 extends Model
{
    use ScopeRole, ScopeTahun;

    protected $table = 'perubahan_jumlah_output_v2';

    protected $guarded = [];
}
