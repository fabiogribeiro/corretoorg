<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $challenges = Challenge::all();
        return view('challenges.index', ['challenges' => $challenges]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('challenges.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Challenge $challenge)
    {
        return view('challenges.show', ['challenge' => $challenge]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Challenge $challenge)
    {
        return view('challenges.edit', ['challenge' => $challenge]);
    }
}
