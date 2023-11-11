<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\Comment;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('challenges.index', ['challenges' => Challenge::all()->groupBy('subject')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Challenge::class);

        return view('challenges.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Challenge $challenge)
    {
        return view('challenges.show', ['challenge' => $challenge, 'comments' => $challenge->comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Challenge $challenge)
    {
        $this->authorize('update', $challenge);

        return view('challenges.edit', ['challenge' => $challenge]);
    }
}
