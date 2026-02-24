<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

trait SetdaResourceAccess
{
    /**
     * Get satuan kerja id untuk user setda atau biro yang login,
     * kalau bukan setda atau biro akan return `null`
     */
    private function getSatkerSetdaBiro(): ?int
    {
        $satkerUser = Auth::user()->satuan_kerja_id;

        return Role::isSetda() || isBiro($satkerUser) ? SATKER_SETDA : null;
    }

    private function restrictBiro()
    {
        $satkerUser = Auth::user()->satuan_kerja_id;

        if (isBiro($satkerUser)) {
            abort(403, 'Anda tidak memiliki hak akses');
        }
    }

    /**
     * Method ini meng-extend App\Http\Controllers\Controller::authorizeBySatuanKerja
     *
     *
     * @see \App\Http\Controllers\Controller::authorizeBySatuanKerja()
     */
    private function authorizeBySatuanKerjaExcSetdaBiro(int $satkerId)
    {
        $isEligible = $this->authorizeBySatuanKerja(modelSatuanKerjaId: $satkerId, abort: false);

        // Khusus biro tetap bisa lihat data setda
        $isEligible = $isEligible ?: isBiroOrSetda($satkerId);

        if (! $isEligible) {
            abort(403, 'Anda tidak memiliki hak akses');
        }
    }
}
