<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Paciente') }}
            </h2>
            <div class="flex space-x-4">
                @if(Auth::user()->is_admin || (Auth::user()->sector && Auth::user()->sector->name == 'Recepção'))
                    <a href="{{ route('pacientes.edit', $patient) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Informações do Paciente -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Paciente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nome</p>
                                <p class="mt-1">{{ $patient->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">CPF</p>
                                <p class="mt-1">{{ $patient->cpf }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Data de Nascimento</p>
                                <p class="mt-1">
                                    {{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : 'Não informado' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Idade</p>
                                <p class="mt-1">
                                    {{ $patient->birth_date ? $patient->birth_date->age . ' anos' : 'Não informado' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Convênio</p>
                                <p class="mt-1">{{ $patient->health_insurance ?: 'Não informado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Número do Convênio</p>
                                <p class="mt-1">{{ $patient->health_insurance_number ?: 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Internação -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações de Internação</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $patient->currentFlow?->status === 'aguardando' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $patient->currentFlow?->status === 'em_atendimento' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ !$patient->currentFlow && $patient->last_flow?->status === 'concluido' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $patient->status_text }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Setor</p>
                                <p class="mt-1">{{ $patient->current_sector?->name ?? 'Não internado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Leito</p>
                                <p class="mt-1">{{ $patient->bed ?: 'Não informado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Data de Internação</p>
                                <p class="mt-1">{{ $patient->currentFlow?->check_in?->format('d/m/Y H:i') ?? 'Não internado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Data de Alta</p>
                                <p class="mt-1">{{ $patient->last_flow?->check_out?->format('d/m/Y H:i') ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Equipe Médica</p>
                                <p class="mt-1">{{ $patient->medical_team ?: 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Observações -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Observações</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $patient->observations ?: 'Nenhuma observação registrada.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('pacientes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            Voltar
        </a>
    </div>
</x-app-layout>

