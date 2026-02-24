<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class LinkPerjanjianKinerja extends Model
{
    use ScopeRole, ScopeTahun;

    protected $table = 'link_perjanjian_kinerja';

    protected $guarded = [];
}
