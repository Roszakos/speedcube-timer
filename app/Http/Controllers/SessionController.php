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
}