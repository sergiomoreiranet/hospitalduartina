<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Triagem - ') }} {{ $patientFlow->patient->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reception.update-triage', $patientFlow) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informações do Paciente -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium mb-4">Informações do Paciente</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p><strong>Nome:</strong> {{ $patientFlow->patient->name }}</p>
                                    <p><strong>CPF:</strong> {{ $patientFlow->patient->cpf }}</p>
                                    <p><strong>Idade:</strong> {{ $patientFlow->patient->birth_date->age }} anos</p>
                                </div>
                            </div>

                            <!-- Nível de Triagem -->
                            <div>
                                <label for="triage_level" class="block text-sm font-medium text-gray-700">Nível de Triagem</label>
                                <select name="triage_level" id="triage_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione...</option>
                                    <option value="emergencia">Emergência</option>
                                    <option value="urgencia">Urgência</option>
                                    <option value="prioridade">Prioridade</option>
                                    <option value="normal">Normal</option>
                                </select>
                                @error('triage_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cor Manchester -->
                            <div>
                                <label for="manchester_color" class="block text-sm font-medium text-gray-700">Cor Manchester</label>
                                <select name="manchester_color" id="manchester_color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione...</option>
                                    <option value="vermelho">Vermelho</option>
                                    <option value="laranja">Laranja</option>
                                    <option value="amarelo">Amarelo</option>
                                    <option value="verde">Verde</option>
                                    <option value="azul">Azul</option>
                                </select>
                                @error('manchester_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sinais Vitais -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium mb-4">Sinais Vitais</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="vital_signs[pressure]" class="block text-sm font-medium text-gray-700">Pressão Arterial</label>
                                        <input type="text" name="vital_signs[pressure]" id="vital_signs[pressure]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: 120/80">
                                        @error('vital_signs.pressure')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vital_signs[temperature]" class="block text-sm font-medium text-gray-700">Temperatura (°C)</label>
                                        <input type="number" step="0.1" name="vital_signs[temperature]" id="vital_signs[temperature]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('vital_signs.temperature')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vital_signs[heart_rate]" class="block text-sm font-medium text-gray-700">Frequência Cardíaca (bpm)</label>
                                        <input type="number" name="vital_signs[heart_rate]" id="vital_signs[heart_rate]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('vital_signs.heart_rate')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vital_signs[respiratory_rate]" class="block text-sm font-medium text-gray-700">Frequência Respiratória (rpm)</label>
                                        <input type="number" name="vital_signs[respiratory_rate]" id="vital_signs[respiratory_rate]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('vital_signs.respiratory_rate')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vital_signs[oxygen_saturation]" class="block text-sm font-medium text-gray-700">Saturação de O2 (%)</label>
                                        <input type="number" name="vital_signs[oxygen_saturation]" id="vital_signs[oxygen_saturation]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('vital_signs.oxygen_saturation')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Sintomas e Fatores de Risco -->
                            <div class="col-span-2">
                                <label for="main_symptoms" class="block text-sm font-medium text-gray-700">Sintomas Principais</label>
                                <textarea name="main_symptoms" id="main_symptoms" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('main_symptoms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label for="risk_factors" class="block text-sm font-medium text-gray-700">Fatores de Risco</label>
                                <textarea name="risk_factors" id="risk_factors" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('risk_factors')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Próximo Setor -->
                            <div class="col-span-2">
                                <label for="next_sector_id" class="block text-sm font-medium text-gray-700">Próximo Setor</label>
                                <select name="next_sector_id" id="next_sector_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione...</option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                                @error('next_sector_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('reception.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 mr-2">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Salvar Triagem</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 