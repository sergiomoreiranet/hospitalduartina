<x-app-layout>
    <div class="bg-[#1a7894] min-h-screen">
        <div class="bg-[#004358] p-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('imagens/logo.png') }}" alt="Logo Hospital" class="h-8 w-auto">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        Painel de Controle
                    </h2>
                </div>
                <div class="flex items-center space-x-4">
                    @if(Auth::user()->is_admin)
                    <a href="{{ route('users.index') }}" class="text-sm text-white hover:text-gray-200">
                        Gerenciamento de Usuários
                    </a>
                    <a href="{{ route('sectors.index') }}" class="text-sm text-white hover:text-gray-200">
                        Administração de Setores
                    </a>
                    @endif
                    <div class="text-sm text-white">
                        Bem-vindo(a), <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Cards de Acesso Rápido -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @if(Auth::user()->is_admin)
                    <!-- Card Atendimento -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Atendimento</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Gestão de pacientes e consultas</p>
                            <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Internação -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-success-100 dark:bg-success-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Internação</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Controle de leitos e pacientes</p>
                            <a href="#" class="inline-flex items-center text-success-600 hover:text-success-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Farmácia -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-warning-100 dark:bg-warning-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Farmácia</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Gestão de medicamentos</p>
                            <a href="#" class="inline-flex items-center text-warning-600 hover:text-warning-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Faturamento -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-info-100 dark:bg-info-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Faturamento</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Controle financeiro</p>
                            <a href="#" class="inline-flex items-center text-info-600 hover:text-info-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @elseif(Auth::user()->sector && Auth::user()->sector->name == 'Enfermagem')
                    <!-- Card Pacientes -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pacientes</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Consultar, evoluir e acompanhar pacientes</p>
                            <a href="{{ route('pacientes.create') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Triagem -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-success-100 dark:bg-success-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Triagem</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Classificação de risco e fila de espera</p>
                            <a href="{{ route('reception.index') }}" class="inline-flex items-center text-success-600 hover:text-success-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Sinais Vitais -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Sinais Vitais</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Registro e acompanhamento</p>
                            <a href="{{ route('patient-flows.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Prescrições -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 opacity-60 cursor-not-allowed">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Prescrições</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Prescrição médica e de enfermagem</p>
                            <a href="#" onclick="alert('Funcionalidade em desenvolvimento')" class="inline-flex items-center text-yellow-600 hover:text-yellow-700 cursor-not-allowed">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Card Exames/Anexos -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 opacity-60 cursor-not-allowed">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-cyan-100 dark:bg-cyan-900 rounded-lg mb-4">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h.01M12 12h.01M9 12h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8a9 9 0 1118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Exames e Anexos</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Visualizar resultados e documentos</p>
                            <a href="#" onclick="alert('Funcionalidade em desenvolvimento')" class="inline-flex items-center text-cyan-600 hover:text-cyan-700 cursor-not-allowed">
                                Acessar
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pacientes Hoje -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pacientes Hoje</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">45</p>
                            </div>
                            <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-full">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-500 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    12%
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">vs. ontem</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leitos Ocupados -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Leitos Ocupados</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">28</p>
                            </div>
                            <div class="p-3 bg-success-100 dark:bg-success-900 rounded-full">
                                <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-red-500 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    8%
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">vs. média</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultas Agendadas -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Consultas Agendadas</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">15</p>
                            </div>
                            <div class="p-3 bg-warning-100 dark:bg-warning-900 rounded-full">
                                <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-500 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    5%
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">vs. semana passada</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exames Realizados -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Exames Realizados</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">32</p>
                            </div>
                            <div class="p-3 bg-info-100 dark:bg-info-900 rounded-full">
                                <svg class="w-6 h-6 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-500 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    15%
                                </span>
                                <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">vs. média diária</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
