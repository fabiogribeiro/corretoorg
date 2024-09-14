<x-app-layout>
    <x-slot:title>{{ __('Welcome') }}</x-slot>

    <div class="bg-gray-100">
        <section class="bg-white py-16">
            <div class="w-3/5 container">
                <header>
                    <h1 class="px-6 py-1 text-5xl font-bold mb-6">
                        {{ __('Aprende matemática sem complicações') }}
                    </h1>
                    <p class="px-6 md:text-xl text-gray-600 mb-12">{{ __('Com uma plataforma que tem tudo o que precisas, quaisquer sejam os teus objetivos de aprendizagem.') }}</p>
                </header>
            </div>
        </section>
        <section class="bg-white pb-16 pt-8">
            <div class="w-full px-6 mb-6">
                <div class="flex flex-col md:flex-row">
                    <div class="w-1/3 pr-3">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Revê os conceitos') }}</h3>
                        <p class="text-slate-500">{{ __('Cada página tem pequenas explicações, exemplos ou figuras ilustrativas e fórmulas chave.') }}</p>
                    </div>
                    <div class="w-1/3 px-3">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Solve exercises') }}</h3>
                        <p class="text-slate-500">{{ __('Para solidificar cada conceito. A prática é assencial para a aprendizagem.') }}</p>
                    </div>
                    <div class="w-1/3 pl-3">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">{{ __('Tira dúvidas') }}</h3>
                        <p class="text-slate-500">{{ __('Pede ajuda ou clarificação numa questão difícil. Vê as explicações disponíveis.') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
