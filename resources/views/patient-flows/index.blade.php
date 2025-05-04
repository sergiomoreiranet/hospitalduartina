<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Fluxo de Pacientes') }}
            </h2>
            <a href="{{ route('patient-flows.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Novo Atendimento
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filtros -->
                    <div class="mb-6">
                        <form action="{{ route('patient-flows.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <label for="sector" class="block text-sm font-medium text-gray-700">Setor</label>
                                <select name="sector" id="sector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Todos os setores</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}" {{ request('sector') == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Todos os status</option>
                                    <option value="aguardando" {{ request('status') == 'aguardando' ? 'selected' : '' }}>Aguardando</option>
                                    <option value="em_atendimento" {{ request('status') == 'em_atendimento' ? 'selected' : '' }}>Em Atendimento</option>
                                    <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filtrar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Lista de Atendimentos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Setor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($patientFlows as $flow)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $flow->patient->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $flow->patient->cpf }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $flow->sector->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $flow->status === 'aguardando' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $flow->status === 'em_atendimento' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $flow->status === 'concluido' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ ucfirst($flow->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $flow->check_in->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('patient-flows.show', $flow) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detalhes</a>
                                            @if($flow->status !== 'concluido')
                                                <form action="{{ route('patient-flows.update-status', $flow) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $flow->status === 'aguardando' ? 'em_atendimento' : 'concluido' }}">
                                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                                        {{ $flow->status === 'aguardando' ? 'Iniciar Atendimento' : 'Finalizar Atendimento' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $patientFlows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 