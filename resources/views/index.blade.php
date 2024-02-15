<x-app-layout>
    <x-slot:title>{{ __('Welcome') }}</x-slot>

    <div class="bg-gray-100">
        <section class="bg-white py-16">
            <div class="container mx-auto text-center">
                <header>
                    <h1 class="py-1 text-6xl font-bold mb-6 bg-gradient-to-r from-blue-500 to-green-500 text-transparent bg-clip-text">
                        {{ __('Practice challenges') }}
                    </h1>
                    <p class="px-6 md:text-xl text-gray-600 mb-12">{{ __('Learn math by solving exercises.') }}</p>
                    <a href="{{ route('challenges.index') }}" class="inline-block px-6 py-3 bg-cyan-600 text-white rounded-md shadow-lg hover:bg-white hover:text-cyan-700 transition">{{ __('Enter') }}</a>
                </header>
            </div>
        </section>
        <section class="pb-16 pt-8">
            <div class="container mx-auto mb-6">
                <header class="p-6 text-4xl text-gray-900 font-bold mb-8 text-center">{{ __('What we offer') }}</header>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Review') }}</h3>
                        <p class="text-slate-500">{{ __('By taking a look at our notes and key formulas.') }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Solve exercises') }}</h3>
                        <p class="text-slate-500">{{ __('Repetition is essential to learning.') }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Ask questions') }}</h3>
                        <p class="text-slate-500">{{ __('Ask for help whenever you get stuck.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
