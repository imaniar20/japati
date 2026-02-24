<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'lke_log';

    protected $fillable = [
        'user_id',
        'action',
        'data',
        'user_agent',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    const ACTION_CLOSE_REVISION = 'close-revision';

    const ACTION_CLOSE_DONE = 'close-done';

    const ACTION_CANCEL_CLOSE_DONE = 'cancel-close-done';

    const ACTION_STORE_PENILAIAN = 'store-penilaian';

    const ACTION_STORE_PENILAIAN_HUMANIS = 'store-penilaian-humanis';

    const ACTION_CLOSE_PENILAIAN_HUMANIS = 'close-penilaian-humanis';

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_agent = request()->header('User-Agent');
        });
    }
}
