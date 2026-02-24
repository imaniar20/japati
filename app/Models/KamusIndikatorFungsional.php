<?php

namespace App\Models;

use App\Traits\ScopeTahun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamusIndikatorFungsional extends Model
{
    use HasFactory, ScopeTahun;

    protected $table = 'kamus_indikator_fungsional';

    protected $guarded = [];

    public function kamusIndikatorFungsional()
    {
        return $this->morphTo(__FUNCTION__, 'model_class', 'model_id');
    }
}
