<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function dashboard(Request $request)
    {
        $challenge_data = Challenge::find(auth()->user()->solved['challenges'] ?? new Collection())
                            ->groupBy('subject')
                            ->map(function ($challenges, $subject) {
                                return [
                                    'list' => $challenges,
                                    'solved_count' => count($challenges),
                                    'total_count' => Challenge::where('subject', $subject)->count()
                                ];
                            });

        return view('dashboard', ['challenge_data' => $challenge_data, 'show_verified' => $request->verified]);
    }

    public function profile()
    {
        return view('profile');
    }
}
