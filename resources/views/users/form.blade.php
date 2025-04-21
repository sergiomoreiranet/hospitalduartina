<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        {{ isset($user) ? 'Editar Usuário' : 'Novo Usuário' }}
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
                    <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Nome -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- CPF -->
                            <div>
                                <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CPF</label>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $user->cpf ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                @error('cpf')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Setor -->
                            <div>
                                <label for="sector_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Setor</label>
                                <select name="sector_id" id="sector_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                    <option value="">Selecione um setor</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}" {{ old('sector_id', $user->sector_id ?? '') == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sector_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo de Usuário -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Usuário</label>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input type="radio" name="user_type" id="user_type_regular" value="regular" 
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ old('user_type', isset($user) ? ($user->is_admin ? '' : ($user->is_sector_admin ? '' : 'checked')) : 'checked') }}>
                                        <label for="user_type_regular" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Usuário Comum</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="user_type" id="user_type_sector_admin" value="sector_admin" 
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ old('user_type', isset($user) && $user->is_sector_admin ? 'checked' : '') }}>
                                        <label for="user_type_sector_admin" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Administrador de Setor</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="user_type" id="user_type_admin" value="admin" 
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{ old('user_type', isset($user) && $user->is_admin ? 'checked' : '') }}>
                                        <label for="user_type_admin" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Administrador do Sistema</label>
                                    </div>
                                </div>
                                @error('user_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Senha -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                                <input type="password" name="password" id="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    {{ !isset($user) ? 'required' : '' }}>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmação de Senha -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Senha</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    {{ !isset($user) ? 'required' : '' }}>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" 
                                class="ml-4 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                {{ isset($user) ? 'Atualizar' : 'Criar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 