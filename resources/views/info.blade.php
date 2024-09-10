<x-app-layout>
    <x-slot:title>{{ __('misc.'.$info) }}</x-slot>

    <div class="bg-gray-100">
        <section class="bg-white py-16 px-4 sm:px-6">
            <div class="container max-w-6xl mx-auto">
                <x-mmd :content="__('misc.'.$info.'.long')"/>
            </div>
        </section>
    </div>
</x-app-layout>
