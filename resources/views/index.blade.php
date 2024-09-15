<x-app-layout>
    <x-slot:title>{{ __('Welcome') }}</x-slot>

    <div class="bg-gray-100">
        <section class="bg-white py-8 md:py-16">
            <div class="w-11/12 md:w-4/5 container mx-auto flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2 md:pr-8 mb-8 md:mb-0">
                    <header>
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 md:mb-6 text-center md:text-left">
                            {{ __('Aprende matemática sem complicações') }}
                        </h1>
                        <p class="text-lg md:text-xl text-gray-600 mb-6 text-center md:text-left">{{ __('Com uma plataforma que tem tudo o que precisas, quaisquer sejam os teus objetivos de aprendizagem.') }}</p>
                        <div class="mb-6 mt-9 text-center md:text-left">
                            <a href="{{ route('challenges.index') }}" class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                {{ __('Enter') }}
                            </a>
                        </div>
                    </header>
                </div>
                <div class="hidden md:flex w-full md:w-1/2 justify-center items-center">
                    <svg class="w-48 h-48 md:w-64 md:h-64 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z" />
                    </svg>
                </div>
            </div>
        </section>
        <section class="bg-white pb-8 md:pb-16 md:pt-8">
            <div class="w-11/12 md:w-4/5 container mx-auto mb-6">
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-1/3 pr-0 md:pr-3 mb-6 md:mb-0">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Revê os conceitos') }}</h3>
                        <p class="text-slate-500">{{ __('Cada página tem pequenas explicações, exemplos ou figuras ilustrativas e fórmulas chave.') }}</p>
                    </div>
                    <div class="w-full md:w-1/3 px-0 md:px-3 mb-6 md:mb-0">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Solve exercises') }}</h3>
                        <p class="text-slate-500">{{ __('Para solidificar cada conceito. A prática é assencial para a aprendizagem.') }}</p>
                    </div>
                    <div class="w-full md:w-1/3 pl-0 md:pl-3">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Tira dúvidas') }}</h3>
                        <p class="text-slate-500">{{ __('Pede ajuda ou clarificação numa questão difícil. Vê as explicações disponíveis.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
