<div x-init="$nextTick(() => { MathJax.typeset() });"
    {{$attributes->merge(['class' => 'markdown-body leading-5 text-justify'])}}>
    {!! Illuminate\Support\Str::markdown($slot, ['html_input' => 'strip']) !!}
</div>
