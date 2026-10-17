<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /** POST /api/auth/login — login dengan NIM dan nama mahasiswa. */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nim' => ['required', 'string'],
            'nama' => ['required', 'string'],
        ]);

        $user = User::where('username', trim($data['nim']))->first();

        // Nama dibandingkan tanpa peka huruf besar/kecil (sama seperti aturan Tugas 3).
        if (! $user || mb_strtolower($user->name) !== mb_strtolower(trim($data['nama']))) {
            return response()->json(['message' => 'NIM dan Nama tidak sesuai.'], 401);
        }

        $plain = Str::random(60);
        $user->forceFill(['api_token' => hash('sha256', $plain)])->save();

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $plain,
            'token_type' => 'Bearer',
            'user' => $this->userData($user),
        ]);
    }

    /** POST /api/auth/logout — mencabut token. */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->forceFill(['api_token' => null])->save();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    /** GET /api/auth/me — data pengguna yang sedang login. */
    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->userData($request->user())]);
    }

    private function userData(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'role' => $user->role,
        ];
    }
}
