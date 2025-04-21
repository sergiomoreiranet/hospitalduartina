<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($sector) ? __('Editar Setor') : __('Novo Setor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ isset($sector) ? route('sectors.update', $sector) : route('sectors.store') }}">
                        @csrf
                        @if(isset($sector))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-label for="name" :value="__('Nome do Setor')" />
                                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $sector->name ?? '')" required autofocus />
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="description" :value="__('Descrição')" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" rows="3">{{ old('description', $sector->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_active', $sector->is_active ?? true) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Setor Ativo') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('sectors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button class="ml-4">
                                {{ isset($sector) ? __('Atualizar') : __('Criar') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 