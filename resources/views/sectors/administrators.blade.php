<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Administradores do Setor: {{ $sector->name }}
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

            <div class="mb-6">
                <a href="{{ route('sectors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Voltar
                </a>
                <a href="{{ route('users.create', ['sector_id' => $sector->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 ml-2">
                    Adicionar Administrador
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($administrators as $admin)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $admin->name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">{{ $admin->email }}</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $admin->cpf }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $admin->is_sector_admin ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $admin->is_sector_admin ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <div class="space-x-2">
                                    <a href="{{ route('users.edit', $admin) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">Editar</a>
                                </div>
                                <form action="{{ route('users.destroy', $admin) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium" onclick="return confirm('Tem certeza que deseja remover este administrador?')">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $administrators->links() }}
            </div>

            @if($availableAdmins->isNotEmpty())
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-white mb-4">Administradores Disponíveis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($availableAdmins as $admin)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $admin->name }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">{{ $admin->email }}</p>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $admin->cpf }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $admin->is_sector_admin ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $admin->is_sector_admin ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>

                                    <div class="flex justify-end items-center mt-4">
                                        <form action="{{ route('sectors.add-administrator', $sector) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                                Adicionar ao Setor
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 