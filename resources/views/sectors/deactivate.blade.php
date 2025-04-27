<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Desativar Setor
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Confirmação de Desativação
                </h3>
                
                
                <div class="mb-6">
                    <p class="text-gray-700 dark:text-gray-300 mb-2">
                        <strong>Setor:</strong> {{ $sector->name }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300">
                        <strong>Descrição:</strong> {{ $sector->description }}
                    </p>
                </div>

                <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Atenção!</p>
                    <p>A desativação deste setor também desativará todos os usuários associados a ele.</p>
                </div>

                <form action="{{ url('/sectors/'.$sector->id.'/direct-deactivate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="current_page" value="{{ $currentPage }}">
                    <input type="hidden" name="sector_id" value="{{ $sector->id }}">
                    <input type="hidden" name="debug_info" value="desativação_via_direct_method">
                    
                    <div class="mb-6">
                        <label for="deactivation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Motivo da Desativação
                        </label>
                        <textarea
                            id="deactivation_reason"
                            name="deactivation_reason"
                            rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Por favor, informe o motivo da desativação deste setor..."
                            required
                        ></textarea>
                    </div>
                    

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('sectors.index', ['page' => $currentPage]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                            Confirmar Desativação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 