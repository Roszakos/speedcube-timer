<?php

namespace App\Http\Controllers;

use App\Models\Solve;
use App\Models\Session;

class SessionController extends Controller
{
    public function getSessionData(Session $session)
    {
        // if (Session::where('hash', '=', $hash)->exists()) {
        //     $sessionId = Session::where('hash', '=', $hash)->first()->id;
        //     return Solve::select('hash', 'time', 'scramble', 'plus2', 'dnf')->where('session_id', '=', $sessionId)->get();
        // }
        if ($session) {
            return Solve::select('hash', 'time', 'scramble', 'plus2', 'dnf')->where('session_id', '=', $session->id)->get();
        } else {
            return response('Wrong session hash', 404);
        }
    }
}