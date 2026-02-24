<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VStrukturOrganisasi extends Model
{
    protected $table = 'v_struktur_organisasi';

    protected $keyType = 'string';

    protected $casts = [
        'id' => 'string',
    ];

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 1)->whereNull('unit_kerja_aktif_selesai');
        });
    }

    /**
     * Get list unit kerja berdasarkan level
     */
    public static function getListUnitKerja(int $satkerId, int $level, bool $withInactive = false): \Illuminate\Database\Eloquent\Collection
    {
        $select = ['id'];
        $isBiro = isBiro($satkerId);

        if ($level == 0) {
            $select[] = 'satuan_kerja_nama';
        } else {
            for ($i = 1; $i <= $level; $i++) {
                $select[] = "lv{$i}_unit_kerja_nama";
            }
        }

        return self::select($select)
            ->where('satuan_kerja_id', parseSatuanKerjaId($satkerId))
            ->where('level', $level)
            ->when($isBiro, fn ($query) => $query->where('lv2_unit_kerja_id', $satkerId)) // jika biro maka ambil data sesuai bironya
            ->when($withInactive, fn (Builder $query) => $query->withoutGlobalScope('active'))
            ->get()
            ->transform(function ($item) use ($level, $isBiro) {
                $unitKerja = $level == 0
                    ? $item->satuan_kerja_nama
                    : $item["lv{$level}_unit_kerja_nama"];
                $minLevel = $isBiro ? 2 : 1; // jika biro cukup ambil sampai level 2

                if ($level > 1) {
                    for ($i = $level - 1; $i >= $minLevel; $i--) {
                        $unitKerja .= " PADA {$item["lv{$i}_unit_kerja_nama"]}";
                    }
                }

                return [
                    'id' => $item->id,
                    'unit_kerja_nama' => $unitKerja,
                ];
            });
    }
}
