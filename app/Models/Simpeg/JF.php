<?php

namespace App\Models\Simpeg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JF extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'simpeg';

    protected $table = 'm_spg_referensi_jf';

    protected $primaryKey = 'jf_id';
}
