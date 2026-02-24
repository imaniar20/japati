<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class ValidasiPerencanaan extends Model
{
    use ScopeRole, ScopeTahun;

    protected $table = 'validasi_perencanaan';

    protected $guarded = [];
}
