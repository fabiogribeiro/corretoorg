<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;

class UserController extends Controller
{
    public function dashboard()
    {
        $solved_challenges = Challenge::find(auth()->user()->solved['challenges']);
        return view('dashboard', ['solved_challenges' => $solved_challenges]);
    }
    public function profile()
    {
        return view('profile');
    }
}
