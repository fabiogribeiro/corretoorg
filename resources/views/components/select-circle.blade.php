@props(['bg'])

<div {{ $attributes->merge(['class' => 'rounded-full w-2 h-2 inline-block' . ' ' . ($bg ?? 'bg-cyan-500')]) }}>
</div>
