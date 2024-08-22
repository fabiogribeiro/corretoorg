<?php

use Illuminate\Support\Facades\File;

return [
    'question_help' =>

        "
            ### Help

            Numbers and expressions are evaluated in [AsciiMath](https://asciimath.org) format. Some questions don't need an answer.
        ",

    'terms' => 'Terms of Use',
    'privacy' => 'Privacy Policy',
    'terms.long' => File::get('terms.md'),
    'privacy.long' => File::get('privacy.md')
];
