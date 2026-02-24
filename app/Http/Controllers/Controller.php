<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Cek permission berdasarkan satuan kerja data dengan user yang sedang login
     *
     * @param  int  $modelSatuanKerjaId  satuan kerja pada model yang akan di cek
     * @param  array  $allowedRoleIds  role id yang diizinkan akses, set `null` untuk mengizinkan semua role dan hanya berdasarkan satuan kerja
     * @param  bool  $abort  set `true` untuk abort 403 jika tidak ada akses, set `false` untuk return boolean
     * @return bool
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeBySatuanKerja(int $modelSatuanKerjaId, ?array $allowedRoleIds = null, bool $abort = true)
    {
        $user = Auth::user();

        if ($allowedRoleIds === null) {
            $allowedRoleIds = Role::ROLES;
        }

        foreach ($allowedRoleIds as $roleId) {
            if ($roleId === Role::SUPER && Role::isSuper()) {
                return true;
            }

            if ($roleId === Role::PEMERINTAH_DAERAH && Role::isPemerintahDaerah() && $modelSatuanKerjaId === $user->satuan_kerja_id) {
                return true;
            }

            if ($roleId === Role::PERANGKAT_DAERAH && Role::isPerangkatDaerah() && $modelSatuanKerjaId === $user->satuan_kerja_id) {
                return true;
            }

            /**
             * Setda bisa akses data milik setda maupun biro
             */
            if ($roleId === Role::SETDA && Role::isSetda() && isBiroOrSetda($modelSatuanKerjaId)) {
                return true;
            }
        }

        if ($abort) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        return false;
    }

    /**
     * Cek permission berdasarkan role user yang sedang login
     *
     * @param  int|array  $roleIds  ID role yang diizinkan, gunakan konstanta dari App\Models\Role supaya rapih
     * @param  bool  $abort  set `true` untuk abort 403 jika tidak ada akses, set `false` untuk return boolean
     * @return bool
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function authorizeByRoles(int|array $roleIds, bool $abort = true)
    {
        $roleIds = is_int($roleIds) ? [$roleIds] : $roleIds;

        if (in_array(Auth::user()->role_id, $roleIds)) {
            return true;
        }

        if ($abort) {
            abort(403, 'Anda tidak memiliki hak akses');
        }

        return false;
    }
}
