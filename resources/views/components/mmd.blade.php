<div class="markdown-body" x-init="$nextTick(() => { MathJax.typeset() });">
    {!! Illuminate\Support\Str::markdown($slot, ['html_input' => 'strip']) !!}
</div>
