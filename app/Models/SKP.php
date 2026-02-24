<?php

namespace App\Models;

use App\Traits\ScopeRole;
use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Model;

class SKP extends Model
{
    use ScopeRole, ScopeTahun;

    protected $connection = 'pgsql'; // fix cross connection db relation

    protected $table = 'skp';

    protected $guarded = [];

    public function skp()
    {
        return $this->morphTo(__FUNCTION__, 'model_class', 'model_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id');
    }
}
