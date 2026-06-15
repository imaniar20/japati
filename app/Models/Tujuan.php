<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    use ScopeTahun;

    protected $table = 'tujuan';

    protected $guarded = [];

    public function misi()
    {
        return $this->belongsTo(Misi::class, 'misi_id');
    }
}
