<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Gerenciamento de Setores
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
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-white">Lista de Setores</h3>
                <a href="{{ route('sectors.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Novo Setor
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($sectors as $sector)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $sector->name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $sector->description ?? 'Sem descrição' }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $sector->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $sector->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>

                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <div class="flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg mr-3">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p>{{ $sector->administrators_count }} Administrador(es)</p>
                                    <p>{{ $sector->regular_users_count }} Usuário(s)</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="space-x-2">
                                    <a href="{{ route('sectors.edit', $sector) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">Editar</a>
                                    <a href="{{ route('sectors.administrators', $sector) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">Administradores</a>
                                    <a href="{{ route('sectors.users', ['sector' => $sector]) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium">Usuários</a>
                                </div>
                                @if($sector->is_active)
                                    <button type="button" 
                                        onclick="showDeactivateModal('{{ $sector->id }}', '{{ route('sectors.destroy', ['sector' => $sector->id]) }}')" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                        Desativar
                                    </button>
                                @else
                                    <form action="{{ route('sectors.restore', $sector) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium">
                                            Reativar
                                        </button>
                                    </form>
                                    <div class="text-sm text-gray-500 mt-1">
                                        Desativado por: {{ $sector->deactivatedBy?->name ?? 'N/A' }}<br>
                                        Em: {{ $sector->deactivated_at?->format('d/m/Y H:i:s') ?? 'N/A' }}<br>
                                        Motivo: {{ $sector->deactivation_reason ?? 'N/A' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $sectors->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de Desativação -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="deactivateModal" style="display: none;"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto" id="deactivateModalContent" style="display: none;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                            Desativar Setor
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Por favor, informe o motivo da desativação do setor.
                            </p>
                        </div>
                    </div>
                </div>
                <form method="POST" id="deactivateForm" onsubmit="return confirmDeactivation(event)">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="current_page" id="currentPage" value="{{ request()->get('page', 1) }}">
                    <div class="mt-5">
                        <textarea
                            name="deactivation_reason"
                            rows="4"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Digite o motivo da desativação..."
                            required
                        ></textarea>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button
                            type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 sm:col-start-2"
                        >
                            Desativar
                        </button>
                        <button
                            type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0"
                            onclick="closeDeactivateModal()"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDeactivateModal(sectorId, formAction) {
            console.log('Showing modal for sector:', sectorId);
            console.log('Form action:', formAction);
            
            const modal = document.getElementById('deactivateModal');
            const modalContent = document.getElementById('deactivateModalContent');
            const form = document.getElementById('deactivateForm');
            const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
            
            // Atualiza o valor da página atual
            document.getElementById('currentPage').value = currentPage;
            
            // Define a action do formulário
            form.action = formAction;
            
            // Exibe o modal
            modal.style.display = 'block';
            modalContent.style.display = 'block';
        }

        function closeDeactivateModal() {
            document.getElementById('deactivateModal').style.display = 'none';
            document.getElementById('deactivateModalContent').style.display = 'none';
        }

        function confirmDeactivation(event) {
            event.preventDefault();
            console.log('Form submission started');
            
            const form = event.target;
            const formData = new FormData(form);
            
            // Log form data
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            console.log('Submitting form to:', form.action);
            form.submit();
            return false;
        }
    </script>
</x-app-layout> 