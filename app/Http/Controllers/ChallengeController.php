<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $query = Challenge::orderBy('title', 'desc');

        if (!auth()->user()?->isAdmin)
            $query = $query->where('stage', 'prod');

        $challenges = $query->get();
        $questionCount = DB::table('questions')->selectRaw('challenge_id, count(*)')->groupBy('challenge_id')->get();
        $solvedCount = Question::find(auth()->user()?->solved['questions'] ?? [])->groupBy('challenge_id')->map(function($item, $key) { return $item->count(); });

        return view('challenges.index', ['challenges' => $challenges, 'questionCount' => $questionCount->pluck('count', 'challenge_id'), 'solvedCount' => $solvedCount]);
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
        $oc_query = Challenge::where('subject', $challenge->subject)->orderBy('title', 'desc');

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

    /**
     * /api/challenges/put
     *
     * Creates a new challenge from outside the application with json request.
     */
    public function put(Request $request)
    {
        if (!auth()->user()->isAdmin) return 'No permission!';

        $data = $request->all();

        $challenge = Challenge::create([
            'title' => $data['title'],
            'subject' => $data['subject'],
            'slug' => Str::slug($data['title'])
        ]);

        foreach ($data['questions'] as $q) {
            $question = new Question;
            $question->challenge_id = $challenge->id;
            $question->statement = $q['statement'];
            $question->answer_data = [];
            $question->answer_data['answer'] = $q['answer'];
            $question->answer_data['type'] = 'multiple-choice';
            $question->answer_data['options'] = [];
            $question->save();
        }

        return $challenge;
    }
}
