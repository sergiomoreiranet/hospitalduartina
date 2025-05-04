<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Encaminhamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('patient-flows.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Paciente -->
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Paciente</label>
                                <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Selecione um paciente</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} - {{ $patient->cpf }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Setor -->
                            <div>
                                <label for="sector_id" class="block text-sm font-medium text-gray-700">Setor</label>
                                <select name="sector_id" id="sector_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Selecione um setor</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sector_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prioridade -->
                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_priority" id="is_priority" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" {{ old('is_priority') ? 'checked' : '' }}>
                                    <label for="is_priority" class="ml-2 block text-sm text-gray-900">
                                        Caso Prioritário
                                    </label>
                                </div>
                                @error('is_priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Observações -->
                            <div class="md:col-span-2">
                                <label for="observations" class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="observations" id="observations" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('observations') }}</textarea>
                                @error('observations')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('patient-flows.index') }}" class="text-sm font-semibold leading-6 text-gray-900">
                                Cancelar
                            </a>
                            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Encaminhar Paciente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 