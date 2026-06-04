<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $user->createToken('simagang-token')->plainTextToken;

        return $this->successResponse('Login berhasil', [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse('Logout berhasil');
    }

    public function profile(Request $request)
    {
        return $this->successResponse('Profil berhasil diambil', $request->user()->load(['student', 'lecturer', 'fieldSupervisor']));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $request->user()->update($data);
        return $this->successResponse('Profil berhasil diperbarui', $request->user());
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['old_password'], $request->user()->password)) {
            return $this->errorResponse('Password lama tidak sesuai', null, 422);
        }

        $request->user()->update([
            'password' => Hash::make($data['new_password']),
        ]);

        return $this->successResponse('Password berhasil diubah');
    }
}
