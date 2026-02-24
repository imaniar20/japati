<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Visi extends Model
{
    use ScopeTahun;

    protected $table = 'visi';

    protected $guarded = [];
}
