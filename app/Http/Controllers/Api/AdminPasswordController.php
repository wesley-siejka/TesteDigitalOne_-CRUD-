<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;

class AdminPasswordController extends Controller
{
    use AuthorizesRequests;

    /**
     * Admin reseta a senha de um usuário.
     */
    public function reset(Request $request, User $user)
    {
        $this->authorize('resetPassword', $user);

        $data = $request->validate([
            'admin_password' => 'required|string',
        ]);

        $admin = $request->user();

        if (!Hash::check($data['admin_password'], $admin->password)) {
            throw ValidationException::withMessages([
                'admin_password' => ['Senha do admin incorreta.'],
            ]);
        }

        $newPassword = '123456';

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return response()->json([
            'message' => 'Senha resetada com sucesso',
            'password' => $newPassword,
        ]);
    }
}
