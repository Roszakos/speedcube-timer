<?php

namespace App\Http\Controllers;

use App\Models\Solve;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function getSessionData($hash)
    {
        if (Session::where('hash', '=', $hash)->exists()) {
            $sessionId = Session::where('hash', '=', $hash)->first()->id;
            return Solve::select('hash', 'time', 'scramble', 'plus2', 'dnf')->where('session_id', '=', $sessionId)->get();
        }
    }

    public function getAllSessions(Request $request)
    {
        $response['user'] = [
            'nickname' => $request->user()->nickname,
            'email' => $request->user()->email
        ];
        $response['sessions'] = Session::select('hash', 'puzzle', 'id')->where('user_id', $request->user()->id)->get();
        foreach ($response['sessions'] as $session) {
            $session['times'] = Solve::select('hash', 'time', 'plus2', 'dnf')->where('session_id', $session['id'])->get();
            unset($session['id']);
        }

        return $response;
    }
}