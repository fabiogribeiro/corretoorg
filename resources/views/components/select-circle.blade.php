@props(['bg'])

<div {{ $attributes->merge(['class' => 'rounded-full w-[10px] h-[10px] inline-block' . ' ' . ($bg ?? 'bg-cyan-500')]) }}>
</div>
