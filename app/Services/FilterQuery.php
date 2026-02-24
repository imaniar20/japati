<?php

namespace App\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;

class FilterQuery
{
    /**
     * Parse filter ke query
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query  query yang akan di filter
     * @param  array|mix  $filters  array filter key-value
     * @param  array  $ignoreKeys  key $filters yang ingin di lewati
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public static function parseFilter($query, $filters, array $ignoreKeys = [])
    {
        if (! is_array($filters)) {
            return $query;
        }

        $getValue = function (string $key) use ($filters, $ignoreKeys) {
            $keys = array_keys($filters);
            $keys = array_filter($keys, fn ($item) => ! in_array($item, $ignoreKeys));

            return $filters[$key] ?? null;
        };

        foreach ($filters as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            if (in_array($key, $ignoreKeys)) {
                continue;
            }

            switch ($key) {
                case 'satuan_kerja_id':
                    if ($value == 1001) {
                        $satkerIds = getSatuanKerjaIds(1001);
                        $query->whereIn('satuan_kerja_id', $satkerIds);
                    } else {
                        $query->where('satuan_kerja_id', $value);
                    }

                    break;
                case 'misi_id':
                    $query->where('misi_id', $value);
                    break;
                case 'tujuan_id':
                    $query->where('tujuan_id', $value);
                    break;
                case 'indikator_tujuan_id':
                    $query->where('indikator_tujuan_id', $value);
                    break;
                case 'sasaran_strategis_id':
                    $query->where('sasaran_strategis_id', $value);
                    break;
                case 'indikator_sasaran_strategis_id':
                    $query->where('indikator_sasaran_strategis_id', $value);
                    break;
                case 'sasaran':
                    $query->where('sasaran', $value);
                    break;
                case 'indikator':
                    $query->where('indikator', $value);
                    break;
                case 'status_validasi':
                    if ($value) {
                        $query->whereHas('skp', fn (Builder $query) => $query->tahunKinerja());
                    } else {
                        $query->whereDoesntHave('skp', fn (Builder $query) => $query->tahunKinerja());
                    }
                    break;
                case 'pengampu':
                    if ($value == '-') {
                        $query->where(fn (Builder $query) => $query
                            ->whereNull('pengampu')
                            ->orWhere(fn (Builder $query) => $query
                                ->whereNull('v_struktur_organisasi_id')
                                ->whereNull('tim_kerja_id')
                            )
                        );
                    } else {
                        $query->where('pengampu', $value);
                    }
                    break;
                case 'tim_kerja_id':
                    if ($getValue('pengampu') != 'tim-kerja') {
                        break;
                    }

                    $query->where('tim_kerja_id', $value);
                    break;
                case 'v_struktur_organisasi_id':
                    if ($getValue('pengampu') != 'unit-kerja') {
                        break;
                    }

                    $query->where('v_struktur_organisasi_id', $value);
                    break;

                case 'is_external':
                    $query->where('is_external', $value);
                    break;
            }
        }

        return $query;
    }
}
