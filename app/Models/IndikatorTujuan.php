<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class IndikatorTujuan extends Model
{
    use ScopeTahun;

    protected $table = 'indikator_tujuan';

    protected $guarded = [];
}
