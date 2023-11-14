<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as PasswordReset;

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

    function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        \Illuminate\Support\Facades\Password::sendResetLink($request->only('email'));

        return response(['email' => $request->email], 200);
    }

    function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ]
        ]);

        $status = PasswordReset::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === PasswordReset::PASSWORD_RESET
            ? response(['success' => true], 200)
            : response(['success' => false], 200);
    }
}
