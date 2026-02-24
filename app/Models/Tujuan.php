<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    use ScopeTahun;

    protected $table = 'tujuan';

    protected $guarded = [];
}
