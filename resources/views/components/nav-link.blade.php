@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-cyan-700 text-cyan-700 leading-5 font-semibold focus:outline-none focus:border-cyan-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-gray-600 leading-5 font-semibold hover:border-gray-300 hover:text-gray-800 focus:outline-none dark:focus:text-gray-300 focus:border-cyan-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
