<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use ScopeTahun;

    protected $table = 'program';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'anggaran' => 'float',
        ];
    }
}
