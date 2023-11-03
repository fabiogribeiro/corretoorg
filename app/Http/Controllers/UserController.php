<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;

class UserController extends Controller
{
    public function dashboard()
    {
        $solved_challenges = Challenge::find(auth()->user()->solved['challenges']);
        $challenge_data = array();

        foreach($solved_challenges as $challenge) {
            $challenge_data[$challenge->subject]['list'][] = $challenge;
        }
        foreach ($challenge_data as $subject => $rest) {
            $challenge_data[$subject]['solved_count'] = count($rest['list']);
            $challenge_data[$subject]['total_count'] = Challenge::where('subject', $subject)->count();
        }

        return view('dashboard', ['challenge_data' => $challenge_data]);
    }
    public function profile()
    {
        return view('profile');
    }
}
