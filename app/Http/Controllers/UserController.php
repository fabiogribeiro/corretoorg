<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;

class UserController extends Controller
{
    public function dashboard()
    {
        $challenge_data = Challenge::find(auth()->user()->solved['challenges'])
                            ->groupBy('subject')
                            ->map(function ($challenges, $subject) {
                                return [
                                    'list' => $challenges,
                                    'solved_count' => count($challenges),
                                    'total_count' => Challenge::where('subject', $subject)->count()
                                ];
                            });

        return view('dashboard', ['challenge_data' => $challenge_data]);
    }
    public function profile()
    {
        return view('profile');
    }
}
