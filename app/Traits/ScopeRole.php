<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ScopeRole
{
    /**
     * Handle filter by `satuan_kerja_id` berdasarkan user yang login
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|array|null  $satkerIds  paksa filter berdasarkan parameter `$satkerIds` nya, kosongkan atau set `null` supaya cek berdasarkan user yang sedang login
     */
    public function scopeRoleSatuanKerja($query, int|array|null $satkerIds = null): \Illuminate\Database\Eloquent\Builder
    {
        if ($satkerIds == 1001) {
            $satkerIds = getSatuanKerjaIds(1001);
        } elseif (! is_null($satkerIds)) {
            $satkerIds = is_array($satkerIds) ? $satkerIds : [$satkerIds];
        } elseif (($user = Auth::user()) && ! is_null($user->satuan_kerja_id)) {
            $satkerIds = getSatuanKerjaIds($user->satuan_kerja_id);
        }

        return $query->when(! is_null($satkerIds), fn ($q) => $q->whereIn('satuan_kerja_id', $satkerIds));
    }
}
