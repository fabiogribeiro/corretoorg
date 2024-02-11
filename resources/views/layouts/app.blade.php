<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Title' }} - correto.org</title>

        <link rel="icon" type="image/png" href="favicon-32x32.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            MathJax = {
                loader: {
                    load: ['input/asciimath', 'input/tex', 'output/chtml', 'ui/menu']
                },
                tex: {
                    inlineMath: [['$', '$'], ['\\(', '\\)']]
                },
                asciimath: {
                    inlineMath: [['`', '`']]
                }
            };
        </script>
        <script type="text/javascript" id="MathJax-script" src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/startup.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <x-footer/>
    </body>
</html>
