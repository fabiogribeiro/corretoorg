<div class="markdown-body font-medium text-gray-700" x-init="$nextTick(() => { MathJax.typeset() });">
    {!! Illuminate\Support\Str::markdown($slot, ['html_input' => 'strip']) !!}
</div>
