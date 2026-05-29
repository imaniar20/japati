<?php

namespace App\Http\Controllers;

use App\Http\Controllers\LKE\PenilaianController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            ->select('id', 'nama', 'username', 'satuan_kerja_id', 'role_id', 'is_active')
            ->with([
                'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
                'role:id,name',
            ])
            ->orderBy('role_id')
            ->orderBy('satuan_kerja_id')
            ->orderBy('nama')
            ->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        $user = User::query()->create($validated);

        return response()->json($this->loadUserRelations($user), 201);
    }

    public function detail(User $user)
    {
        return response()->json($this->loadUserRelations($user));
    }

    public function update(Request $request, User $user)
    {
        $validated = $this->validateUser($request, $user);

        if (! isset($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($this->loadUserRelations($user));
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Tidak bisa menghapus user yang sedang login'], 400);
        }

        $user->delete();

        return response()->json();
    }

    public function roles()
    {
        $roles = Role::query()
            ->select('id', 'name')
            ->orderBy('id')
            ->get();

        return response()->json($roles);
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
            ->where('id', $userId)
            ->update([
                'is_active' => $isActive,
            ]);
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $passwordRules = $user ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'];
        $usernameRule = Rule::unique('users', 'username');

        if ($user) {
            $usernameRule->ignore($user->id);
        }

        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                $usernameRule,
            ],
            'password' => $passwordRules,
            'satuan_kerja_id' => ['nullable', 'integer', 'exists:satuan_kerja,satuan_kerja_id'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function loadUserRelations(User $user): User
    {
        return $user->load([
            'satuanKerja:satuan_kerja_id,satuan_kerja_nama',
            'role:id,name',
        ]);
    }
}
