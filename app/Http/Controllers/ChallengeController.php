<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Challenge;
use App\Models\Question;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Challenge::orderBy('order_key', 'asc');

        if (!($isAdmin = auth()->user()?->isAdmin))
            $query = $query->where('stage', 'prod');

        $challenges = $query->get();

        $questionCount = DB::table('questions')
                        ->selectRaw('challenge_id, count(*)')
                        ->groupBy('challenge_id')->get()
                        ->pluck('count', 'challenge_id');

        $solvedCount = Question::find(auth()->user()?->solved['questions'] ?? [])
                        ->groupBy('challenge_id')
                        ->map(function($item, $key) {
                            return $item->count();
                        });

        foreach ($challenges as $challenge) {
            $challenge->url = route('challenges.show', ['challenge' => $challenge]);

            if ($isAdmin)
                $challenge->title = $challenge->title.' - '.$challenge->stage;

            $challenge->questionCount = $questionCount[$challenge->id] ?? 0;
            $challenge->solvedCount = $solvedCount[$challenge->id] ?? 0;

            if ($challenge->solvedCount === $challenge->questionCount) {
                $challenge->state = 'solved';
            }
            else {
                $challenge->state = $challenge->solvedCount > 0 ? 'progress' : 'unsolved';
            }
        }

        return view('challenges.index', ['challenges' => $challenges->toArray(),
                                        'subjects' => $challenges->pluck('subject')->unique()->toArray()]);
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
        $oc_query = Challenge::where('subject', $challenge->subject)->orderBy('order_key', 'asc');

        if (!auth()->user()?->isAdmin)
            $oc_query = $oc_query->where('stage', 'prod');

        return view('challenges.show', ['challenge' => $challenge, 'comments' => $challenge->comments, 'other_challenges' => $oc_query->get()]);
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
