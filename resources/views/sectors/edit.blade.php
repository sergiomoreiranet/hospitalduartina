<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Editar Setor
                    </h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-white">
                        Bem-vindo(a), <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('sectors.update', $sector) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nome do Setor -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Setor</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $sector->name) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $sector->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600"
                                        {{ old('is_active', $sector->is_active) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Setor Ativo</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sectors.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="ml-4 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 