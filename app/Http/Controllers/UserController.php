<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LKE\PenilaianController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        /** @var User */
        $user = Auth::user();

        $user->load('satuanKerja:satuan_kerja_id,satuan_kerja_nama');
        $user['lke_penilaian_satuan_kerja_ids'] = PenilaianController::mappingValidatorByAuth();

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function hello()
    {
        return response()->json([
            'message' => '<!-- API System Online -->',
        ]);
    }

    public function index()
    {
        $users = User::query()
            ->select('id', 'nama', 'username', 'satuan_kerja_id', 'is_active')
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            ])
            ->whereIn('role_id', [Role::PEMERINTAH_DAERAH, Role::PERANGKAT_DAERAH])
            ->orderBy('satuan_kerja_id')
            ->get();

        return response()->json($users);
    }

    public function enable(int $user)
    {
        $this->setActive($user, true);

        return response()->json([
            'status' => true,
        ]);
    }

    public function disable(int $user)
    {
        $this->setActive($user, false);

        return response()->json([
            'status' => true,
        ]);
    }

    private function setActive(int $userId, bool $isActive)
    {
        User::query()
            ->whereIn('role_id', [Role::PEMERINTAH_DAERAH, Role::PERANGKAT_DAERAH])
            ->where('id', $userId)
            ->update([
                'is_active' => $isActive,
            ]);
    }
}
