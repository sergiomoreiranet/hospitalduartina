<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Perfil do Usuário
                    </h2>
                </div>
                <div class="text-sm text-white">
                    Bem-vindo(a), <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 gap-6">
                <!-- Informações do Perfil -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Perfil</h3>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Atualizar Senha -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Atualizar Senha</h3>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Excluir Conta -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Excluir Conta</h3>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</x-app-layout>
