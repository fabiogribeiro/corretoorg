<?php

namespace App\View;

use App\Models\Challenge;
use App\Models\Question;

trait QuestionTrait
{
    public Challenge $challenge;
    public Question $question;
    public bool $solved;
    public bool $submitted = false;
    public $answer = '';

    protected function premount()
    {
        $this->challenge = $this->question->challenge;

        $this->solved = in_array($this->question->id, auth()->user()?->solved['questions'] ?? []);
    }

    public function submitForm()
    {
        $user = auth()->user();
        if (in_array($this->question->id, $user->solved['questions'])) return;

        if ($this->isCorrectAnswer()) {
            $this->answer = $this->question->answer_data['answer'];

            $this->solved = true;
            $user->solved['questions'][] = $this->question->id;

            if (!in_array($this->challenge->id, $user->solved['challenges']) &&
                $this->challenge->questions->except($user->solved['questions'])->isEmpty()) {

                $user->solved['challenges'][] = $this->challenge->id;
            }

            $user->save();
        }
        else {
            $this->submitted = true;
        }
    }

    public function unsubmit()
    {
        $this->submitted = false;
    }

    public function redo()
    {
        $this->solved = false;
        $user = auth()->user();
        $user->solved['questions'] = array_values(array_diff($user->solved['questions'], [$this->question->id]));
        $user->save();
    }

    abstract protected function isCorrectAnswer(): bool;
}
