<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class NilaiSakipPemda extends Model
{
    use ScopeTahun;

    protected $table = 'nilai_sakip_pemda';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'nilai' => 'float',
            'efisiensi' => 'float',
        ];
    }
}
