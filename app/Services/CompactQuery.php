<?php

namespace App\Services;

class CompactQuery
{
    /**
     * Set compact menggunakan keyBy, hasil sudah di pagination
     *
     * @param  Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder  $query  query yang akan di proses
     * @param  string|closure  $sortBy  urutan sebelum dilakukan compact saat $isCompact = true | asc | null last | untuk mengurutkan data yang akan diambil sebelum di keyBy
     * @param  string  $keyBy  key yang digunakan saat $isCompact = true
     * @param  string|closure  $sortBy2  urutan setelah dilakukan compact saat $isCompact = true | asc | null last | untuk mengurutkan hasil yang akan ditampilkan
     * @param  int  $perpage  jumlah data per page saat saat $isCompact = false
     * @return Illuminate\Pagination\LengthAwarePaginator|App\Supports\LengthAwarePaginator
     */
    public static function compact($query, bool $isCompact, $sortBy, string $keyBy = 'indikator_sasaran_strategis_id', $sortBy2 = 'indikator_sasaran_strategis_id', int $perpage = 20)
    {
        return (new self)->doCompact($query, $isCompact, $sortBy, $keyBy, $sortBy2, $perpage);
    }

    /**
     * Set compact menggunakan keyBy, hasil sudah di pagination
     *
     * @param  Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder  $query  query yang akan di proses
     * @param  string|closure  $sortBy  urutan sebelum dilakukan compact | asc | null last | untuk mengurutkan data yang akan diambil sebelum di keyBy
     * @param  string  $keyBy  key yang digunakan untuk compact
     * @param  string|closure  $sortBy2  urutan setelah dilakukan compact | asc | null last | untuk mengurutkan hasil yang akan ditampilkan
     * @return Illuminate\Support\Collection
     */
    public static function compactWithoutPagination($query, $sortBy, string $keyBy = 'indikator_sasaran_strategis_id', $sortBy2 = 'indikator_sasaran_strategis_id')
    {
        return (new self)->doCompact($query, true, $sortBy, $keyBy, $sortBy2, null);
    }

    private function doCompact($query, bool $isCompact, $sortBy, string $keyBy = 'indikator_sasaran_strategis_id', $sortBy2 = 'indikator_sasaran_strategis_id', $perpage = 20)
    {
        if (! $isCompact) {
            return $query->when($perpage === null,
                function ($query) {
                    return $query->get();
                },
                function ($query) use ($perpage) {
                    return $query->paginate($perpage);
                }
            );
        }

        return $query->get()
            ->when(is_callable($sortBy), function ($collection) use ($sortBy) {
                return $collection->sortBy($sortBy);
            })
            ->when(! is_callable($sortBy), function ($collection) use ($sortBy) {
                // set null last
                return $collection->sortBy(function ($item) use ($sortBy) {
                    return $item->$sortBy === null ? PHP_INT_MAX : $item->$sortBy;
                });
            })
            ->keyBy($keyBy) // compact
            ->when(is_callable($sortBy2), function ($collection) use ($sortBy2) {
                return $collection->sortBy($sortBy2);
            })
            ->when(! is_callable($sortBy2), function ($collection) use ($sortBy2) {
                // set null last
                return $collection->sortBy(function ($item) use ($sortBy2) {
                    return $item->$sortBy2 === null ? PHP_INT_MAX : $item->$sortBy2;
                });
            })
            ->when($perpage === null, function ($collection) {
                return $collection->values();
            })
            ->when($perpage !== null, function ($collection) use ($perpage) {
                return $collection->paginate($perpage); // custom paginate method from macro
            });
    }
}
