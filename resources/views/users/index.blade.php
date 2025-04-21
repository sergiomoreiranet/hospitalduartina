@php
    $sectors = App\Models\Sector::withCount(['administrators', 'regularUsers'])->get();
@endphp

<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Gerenciamento de Usuários
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
                <h3 class="text-lg font-medium text-white">Lista de Usuários</h3>
                <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Novo Usuário
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($users as $user)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $user->name }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">{{ $user->email }}</p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $user->cpf }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @if($user->is_admin)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Administrador Geral
                                        </span>
                                    @endif
                                    @if($user->is_sector_admin)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Admin. de Setor
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($user->sector)
                                <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg mr-3">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    Setor: {{ $user->sector->name }}
                                </div>
                            @endif

                            <div class="flex justify-between items-center mt-4">
                                <div class="space-x-2">
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">Editar</a>
                                </div>
                                @if(!$user->is_admin || Auth::user()->id !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            Excluir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-semibold mb-4">Estatísticas por Setor</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($sectors as $sector)
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold mb-2">{{ $sector->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ $sector->description }}</p>
                                
                                <div class="flex justify-between items-center">
                                    <div class="text-center">
                                        <span class="block text-2xl font-bold text-blue-600">{{ $sector->administrators_count }}</span>
                                        <span class="text-sm text-gray-500">Administradores</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="block text-2xl font-bold text-green-600">{{ $sector->regular_users_count }}</span>
                                        <span class="text-sm text-gray-500">Usuários</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('sectors.administrators', $sector) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                        Ver detalhes →
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 