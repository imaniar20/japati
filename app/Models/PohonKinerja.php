<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PohonKinerja extends Model
{
    protected $table = 'pohon_kinerja';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';
}
