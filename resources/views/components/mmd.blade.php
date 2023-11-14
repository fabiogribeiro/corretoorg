<div x-init="$nextTick(() => { MathJax.typeset() });"
    {{$attributes->merge(['class' => 'markdown-body font-medium text-gray-700'])}}>
    {!! Illuminate\Support\Str::markdown($slot, ['html_input' => 'strip']) !!}
</div>
