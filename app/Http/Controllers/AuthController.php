<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (filter_var($request['emailOrNick'], FILTER_VALIDATE_EMAIL)) {
            $request['email'] = $request['emailOrNick'];
            unset($request['emailOrNick']);

            $credentials = $request->validate([
                'email' => 'required|email|string',
                'password' => [
                    'required'
                ]
            ]);
        } else {
            $request['nickname'] = $request['emailOrNick'];
            unset($request['emailOrNick']);

            $credentials = $request->validate([
                'nickname' => 'required|string',
                'password' => [
                    'required'
                ]
            ]);
        }

        if (!Auth::attempt($credentials)) {
            return response([
                'error' => 'Provided credentials are not correct'
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        $userdata = User::select('nickname', 'email', 'first_name', 'last_name', 'image')->where('id', $user->id)->first();
        $userdata['image'] = $userdata['image'] ? URL::to($userdata['image']) : null;
        return response([
            'user' => $userdata,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nickname' => 'required|string',
            'email' => 'email|required|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ]
        ]);

        $user = User::create([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
}