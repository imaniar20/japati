<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use ScopeTahun;

    protected $table = 'kegiatan';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'anggaran' => 'float',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
