<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Challenge;
use App\Models\Question;

class ApiController extends Controller
{
    /**
     * /api/challenges/create
     *
     * Creates or updates challenge from outside the application with json request.
     */
    public function putChallenge(Request $request)
    {
        if (!auth()->user()->isAdmin) return 'No permission!';

        $data = $request->all();

        $challenge = Challenge::updateOrCreate([
                'slug' => Str::slug($data['title']),
            ], [
                'title' => $data['title'],
                'subject' => $data['subject'],
                'body' => $data['body'],
            ]
        );

        return $challenge;
    }

    /**
     * /api/questions/create
     *
     * Creates or updates questions from outside the application with json request.
     */
    public function putQuestion(Request $request)
    {
        if (!auth()->user()->isAdmin) return 'No permission!';

        $data = $request->all();

        $values = [
            'challenge_id' => $data['challenge_id'],
            'statement' => $data['statement'],
            'order_key' => $data['order_key'],
            'explanation' => $data['explanation'],
            'answer_data' => $data['answer_data'],
        ];

        if ($question = Question::find($data['id']))
            $question->update($values);
        else
            $question = Question::create($values);

         return $question;
    }
}
