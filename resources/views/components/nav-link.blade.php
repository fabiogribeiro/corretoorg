@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-cyan-600 text-cyan-700 font-semibold leading-5 focus:outline-none focus:border-cyan-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-gray-700 font-semibold leading-5 hover:text-cyan-700 hover:border-gray-300 focus:outline-none focus:text-cyan-700 dark:focus:text-gray-300 focus:border-cyan-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
