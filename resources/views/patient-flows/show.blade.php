<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Atendimento') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('patient-flows.edit', $patientFlow) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Editar
                </a>
                <form action="{{ route('patient-flows.destroy', $patientFlow) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700" onclick="return confirm('Tem certeza que deseja cancelar este atendimento?')">
                        Cancelar Atendimento
                    </button>
                </form>
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
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->patient->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">CPF</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->patient->cpf }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Data de Nascimento</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->patient->birth_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Idade</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->patient->age }} anos</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Atendimento -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Atendimento</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Setor</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->sector->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($patientFlow->status === 'aguardando') bg-yellow-100 text-yellow-800
                                    @elseif($patientFlow->status === 'em_atendimento') bg-green-100 text-green-800
                                    @elseif($patientFlow->status === 'concluido') bg-blue-100 text-blue-800
                                    @elseif($patientFlow->status === 'cancelado') bg-red-100 text-red-800
                                    @endif">
                                    {{ $patientFlow->status_text }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Entrada</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->check_in->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tempo de Atendimento</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->duration }} minutos</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Posição na Fila</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->queue_position }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Caso Prioritário</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->is_priority ? 'Sim' : 'Não' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Observações e Conclusão -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Observações e Conclusão</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Observações</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->observations ?: 'Nenhuma observação registrada.' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Conclusão</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $patientFlow->conclusion ?: 'Nenhuma conclusão registrada.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    @if($patientFlow->status === 'aguardando')
                        <div class="mt-6">
                            <form action="{{ route('patient-flows.start-attendance', $patientFlow) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Iniciar Atendimento
                                </button>
                            </form>
                        </div>
                    @elseif($patientFlow->status === 'em_atendimento')
                        <div class="mt-6">
                            <form action="{{ route('patient-flows.finish-attendance', $patientFlow) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Finalizar Atendimento
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 