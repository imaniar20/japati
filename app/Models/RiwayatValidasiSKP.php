<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatValidasiSKP extends Model
{
    use HasFactory, ScopeTahun;

    protected $table = 'riwayat_validasi_skp';

    protected $guarded = [];

    const STATUS_REJECTED = 0;

    const STATUS_VALIDATED = 1;

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeValidated($query)
    {
        return $query->where('status', self::STATUS_VALIDATED);
    }

    public function riwayatValidasiSkp()
    {
        return $this->morphTo(__FUNCTION__, 'model_class', 'model_id');
    }
}
