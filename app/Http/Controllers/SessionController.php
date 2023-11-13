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
        $response['sessions'] = Session::select('hash', 'puzzle', 'id', 'start_date')
            ->where('user_id', $request->user()->id)
            ->orderBy('start_date', 'desc')
            ->get();
        foreach ($response['sessions'] as $session) {
            $session['times'] = Solve::select('hash', 'time', 'scramble', 'plus2', 'dnf')
                ->where('session_id', $session['id'])
                ->get();
            unset($session['id']);
        }

        $response['sessions'] = $response['sessions']->filter(function ($item) {
            return count($item['times']);
        })->values();

        return $response;
    }
}