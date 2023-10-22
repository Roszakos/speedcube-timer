<?php

namespace App\Http\Controllers;

use App\Models\Solve;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SolveController extends Controller
{
    public function store(Request $request)
    {
        if ($request['hash'] && strlen($request['hash']) == 22) {
            if (Session::where('hash', '=', $request['hash'])->exists()) {
                $request['session_id'] = Session::where('hash', '=', $request['hash'])->first()->id;
            } else {
                $request['user_id'] = $request->user()->id;
                $data = $request->validate([
                    'user_id' => 'required|exists:Users,id',
                    'hash' => 'required|alpha_num:ascii',
                    'puzzle' => [
                        'required',
                        Rule::in([
                            Solve::PUZZLE_TYPE_2X2,
                            Solve::PUZZLE_TYPE_3X3,
                            Solve::PUZZLE_TYPE_4X4,
                            Solve::PUZZLE_TYPE_5X5,
                            Solve::PUZZLE_TYPE_6X6,
                            Solve::PUZZLE_TYPE_7X7
                        ])
                    ],
                ]);

                $session = Session::create($data);

                $request['session_id'] = $session->id;
            }

            $request['hash'] = substr(bin2hex(random_bytes(22)), 0, 22);

            $data = $request->validate([
                'session_id' => 'required',
                'hash' => 'required|alpha_num:ascii',
                'time' => 'required|numeric',
                'scramble' => 'required|string'
            ]);

            if ($solve = Solve::create($data)) {
                return $solve->hash;
            }
        } else {
            return response('wrong session hash');
        }
    }

    public function getSessionSolves(Request $request)
    {
        if (Session::where('hash', '=', $request['hash'])->exists()) {
            $sessionId = Session::where('hash', '=', $request['hash'])->first()->id;
            return Solve::select('hash', 'time', 'scramble')->where('session_id', '=', $sessionId)->get();
        }
    }
}