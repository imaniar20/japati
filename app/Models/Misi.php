<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Misi extends Model
{
    use ScopeTahun;

    protected $table = 'misi';

    protected $guarded = [];

    public function visi()
    {
        return $this->belongsTo(Visi::class, 'visi_id');
    }
}
