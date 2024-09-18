<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Challenge;
use App\Models\Question;

class ApiController extends Controller
{
    /**
     * /api/challenges/
     *
     * Creates or updates challenge from outside the application with json request.
     */
    public function putChallenges(Request $request)
    {
        if (!auth()->user()->isAdmin) return 'No permission!';

        $data = $request->all();

        $challenge = Challenge::updateOrCreate([
            ['slug' => Str::slug($data['title'])], [
                'title' => $data['title'],
                'subject' => $data['subject'],
                'body' => $data['body'],
            ]
        ]);

        return $challenge;
    }
}
