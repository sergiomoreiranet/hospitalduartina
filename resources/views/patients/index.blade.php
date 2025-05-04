<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pacientes') }}
            </h2>
            <a href="{{ route('pacientes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Novo Paciente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Nascimento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Convênio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($patients as $patient)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $patient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $patient->cpf }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $patient->birth_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $patient->health_insurance ?? 'Particular' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $patient->ativo ? 'Em atendimento' : 'Alta' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('pacientes.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                            @if(Auth::user()->is_admin || (Auth::user()->sector && Auth::user()->sector->name == 'Recepção'))
                                                <a href="{{ route('pacientes.edit', $patient) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Editar</a>
                                                <form action="{{ route('pacientes.destroy', $patient) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja desativar este paciente?')">
                                                        Desativar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
