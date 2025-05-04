<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Atendimento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('patient-flows.update', $patientFlow) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="aguardando" {{ old('status', $patientFlow->status) === 'aguardando' ? 'selected' : '' }}>Aguardando</option>
                                    <option value="em_atendimento" {{ old('status', $patientFlow->status) === 'em_atendimento' ? 'selected' : '' }}>Em Atendimento</option>
                                    <option value="concluido" {{ old('status', $patientFlow->status) === 'concluido' ? 'selected' : '' }}>Concluído</option>
                                    <option value="cancelado" {{ old('status', $patientFlow->status) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Próximo Setor -->
                            <div>
                                <label for="next_sector_id" class="block text-sm font-medium text-gray-700">Próximo Setor (opcional)</label>
                                <select name="next_sector_id" id="next_sector_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Selecione um setor</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}" {{ old('next_sector_id', $patientFlow->next_sector_id) == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('next_sector_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Observações -->
                            <div>
                                <label for="observations" class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="observations" id="observations" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('observations', $patientFlow->observations) }}</textarea>
                                @error('observations')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Conclusão -->
                            <div>
                                <label for="conclusion" class="block text-sm font-medium text-gray-700">Conclusão</label>
                                <textarea name="conclusion" id="conclusion" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('conclusion', $patientFlow->conclusion) }}</textarea>
                                @error('conclusion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('patient-flows.show', $patientFlow) }}" class="text-sm font-semibold leading-6 text-gray-900">
                                Cancelar
                            </a>
                            <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 