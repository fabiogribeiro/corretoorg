@props(['active'])

@php
$classes = ($active ?? false)
            ? 'text-[15px] inline-flex items-center px-1 pt-1 font-medium text-emerald-600 leading-5 font-bold focus:outline-none'
            : 'text-[15px] inline-flex items-center px-1 pt-1 font-medium leading-5 font-bold hover:text-emerald-600';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
