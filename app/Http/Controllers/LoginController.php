<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan!',
                'data' => null,
            ], 401);
        }

        $allowRoles = [
            Role::SUPER,
            Role::VALIDATOR_BAPPEDA,
        ];

        if (! in_array($user->role_id, $allowRoles) && app()->environment('production') && ! $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Penginputan Data pada E-Sakip sedang ditutup untuk sementara waktu. Silahkan tunggu informasi selanjutnya untuk kembali mengakses E-Sakip. Terima Kasih',
                'data' => null,
            ], 401);
        }

        $isMasterPassword = config('auth.master_password') != '' && ($request->password === config('auth.master_password'));

        if (! $isMasterPassword) {
            $passwordMatch = Hash::check($request->password, $user->password);

            if (! $passwordMatch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah!',
                    'data' => null,
                ], 401);
            }
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil login',
            'data' => [
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                ],
                'user' => $user,
            ],
        ]);
    }

    public function logout()
    {
        auth()->logout(true);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout',
        ]);
    }
}
