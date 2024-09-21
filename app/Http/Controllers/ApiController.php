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

    /**
     * /api/questions/create_all
     *
     * Creates or updates questions from a challenge outside the application with json request.
     *
     * Assumes the existing questions are the same we're trying to update and add extra if needed.
     * Deleting or reordering can easily be done manually if needed for now.
     */
    public function putQuestions(Request $request)
    {
        if (!auth()->user()->isAdmin) return 'No permission!';

        $requestQuestions = $request->all();
        $questions = Challenge::find($requestQuestions[0]['challenge_id'])->questions()->orderBy('order_key')->get();

        $i = 0;
        foreach ($questions as $question) {
            $question->update($requestQuestions[$i]);
            $i++;
        }

        $result = [];
        for (; $i < count($requestQuestions); $i++) {
            array_push($result, Question::create($requestQuestions[$i]));
        }

        return $result;
    }
}
