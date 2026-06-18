<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanKerja extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'satuan_kerja';

    protected $primaryKey = 'satuan_kerja_id';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    /**
     * Get satuan kerja untuk keperluan filter,
     * sudah di format
     *
     * @return App\Models\SatuanKerja
     */
    public static function listForFilter()
    {
        return self::select('satuan_kerja_id', 'satuan_kerja_nama')
            ->get()
            ->prepend([
                'satuan_kerja_id' => null,
                'satuan_kerja_nama' => 'Semua Satuan Kerja',
            ]);
    }
}
