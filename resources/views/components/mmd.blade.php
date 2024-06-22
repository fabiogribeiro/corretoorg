@props(['content'])

<div x-init="$nextTick(() => { MathJax.typeset([$el]) });" {{ $attributes->merge(['class' => 'markdown-body leading-5 text-justify']) }}>
    {!! Illuminate\Support\Str::markdown($content) !!}
</div>
