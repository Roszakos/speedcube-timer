<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'currentPassword' => 'required',
            'newPassword' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ]
        ]);

        if (!password_verify($data['currentPassword'], $request->user()->password)) {
            return response([
                'errors' => [
                    'password' => [
                        'Current password is not correct.'
                    ]
                ]
            ], 422);
        }
        return $request->user()->update(['password' => bcrypt($data['newPassword'])]);
    }
}
