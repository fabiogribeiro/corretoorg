<?php

use Illuminate\Support\Facades\File;

return [
    'question_help' =>
        "
            ### Ajuda

            Números e expressões são avaliados no formato [AsciiMath](https://asciimath.org). Certas questões não precisam de resposta.
        ",

    'terms' => 'Termos de Utilização',
    'privacy' => "Política de Privacidade",
    'terms.long' => File::get('termos.md'),
    'privacy.long' => File::get('privacidade.md'),
];
