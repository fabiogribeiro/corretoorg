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
        $query = Challenge::orderBy('order_key', 'asc');
        $isAdmin = auth()->user()?->isAdmin;

        if (!$isAdmin)
            $query = $query->where('stage', 'prod');

        $challenges = $query->get();
        $questionCount = DB::table('questions')
                        ->selectRaw('challenge_id, count(*)')
                        ->groupBy('challenge_id')->get()
                        ->pluck('count', 'challenge_id');
        $solvedCount = Question::find(auth()->user()?->solved['questions'] ?? [])->groupBy('challenge_id')->map(function($item, $key) { return $item->count(); });

        foreach ($challenges as $challenge) {
            $challenge->url = route('challenges.show', ['challenge' => $challenge]);

            if ($isAdmin)
                $challenge->title = $challenge->title.' - '.$challenge->stage;

            $challenge->questionCount = $questionCount[$challenge->id] ?? 0;
            $challenge->solvedCount = $solvedCount[$challenge->id] ?? 0;

            if ($challenge->solvedCount === $challenge->questionCount) {
                $challenge->state = 'solved';
            }
            elseif ($challenge->solvedCount > 0) {
                $challenge->state = 'progress';
            }
            else {
                $challenge->state = 'unsolved';
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
     * /api/challenges/
     *
     * Creates a new challenge from outside the application with json request.
     */
    public function post(Request $request)
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
            $question->explanation = $q['explanation'] ?? "";
            $question->answer_data = [];
            $question->answer_data['answer'] = $q['answer'];
            $question->answer_data['type'] = $q['type'];
            $question->answer_data['options'] = [];
            $question->save();
        }

        return $challenge;
    }

     /**
     * /api/challenges/{id}
     *
     * Get challenge.
     */
    public function get(int $id)
    {
        return Challenge::find($id);
    }

    /**
     * /api/challenges/{id}
     *
     * Updates challenge.
     */
    public function put(Request $request, int $id)
    {
        if (!auth()->user()->isAdmin) return 'No permission!';

        $data = $request->all();

        $challenge = Challenge::find($id);

        $title = $data['title'] ?? $challenge->title;
        $challenge->update([
            'title' => $title,
            'subject' => $data['subject'] ?? $challenge->subject,
            'slug' => Str::slug($title)
        ]);

        if (isset($data['questions'])) {
            Question::destroy($challenge->questions->pluck('id'));

            foreach ($data['questions'] as $q) {
                $question = new Question;
                $question->challenge_id = $challenge->id;
                $question->statement = $q['statement'];
                $question->explanation = $q['explanation'] ?? "";
                $question->answer_data = [];
                $question->answer_data['answer'] = $q['answer'];
                $question->answer_data['type'] = $q['type'];
                $question->answer_data['options'] = [];
                $question->save();
            }
        }

        return $challenge;
    }
}
