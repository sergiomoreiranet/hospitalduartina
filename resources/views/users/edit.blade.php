<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <div>
                        <h2 class="font-semibold text-xl text-white leading-tight">
                            Editar Usuário
                        </h2>
                        <p class="text-sm text-gray-300 mt-1">
                            {{ $user->name }}
                        </p>
                    </div>
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
                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nome')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="cpf" :value="__('CPF')" />
                                <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" :value="old('cpf', $user->cpf)" required />
                                <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Nova Senha (opcional)')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label :value="__('Tipo de Usuário')" />
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center">
                                            <input type="radio" name="user_type" id="user_type_regular" value="regular" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ !$user->is_admin && !$user->is_sector_admin ? 'checked' : '' }}>
                                            <label for="user_type_regular" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Usuário Comum</label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="radio" name="user_type" id="user_type_admin" value="admin" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $user->is_admin ? 'checked' : '' }}>
                                            <label for="user_type_admin" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Administrador do Sistema</label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="radio" name="user_type" id="user_type_sector_admin" value="sector_admin" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $user->is_sector_admin ? 'checked' : '' }}>
                                            <label for="user_type_sector_admin" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Administrador de Setor</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="sector_id" :value="__('Setor')" />
                                    <select id="sector_id" name="sector_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Selecione um setor</option>
                                        @foreach($sectors as $sector)
                                            <option value="{{ $sector->id }}" {{ old('sector_id', $user->sector_id) == $sector->id ? 'selected' : '' }}>
                                                {{ $sector->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('sector_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Atualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 