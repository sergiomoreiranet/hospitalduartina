<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('pacientes.update', $patient) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dados Pessoais -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Dados Pessoais</h3>
                                
                                <div>
                                    <x-input-label for="name" value="Nome Completo" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $patient->name)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="cpf" value="CPF" />
                                    <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $patient->cpf)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                                </div>

                                <div>
                                    <x-input-label for="rg" value="RG" />
                                    <x-text-input id="rg" name="rg" type="text" class="mt-1 block w-full" :value="old('rg', $patient->rg)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('rg')" />
                                </div>

                                <div>
                                    <x-input-label for="birth_date" value="Data de Nascimento" />
                                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $patient->birth_date->format('Y-m-d'))" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                                </div>

                                <div>
                                    <x-input-label for="gender" value="Gênero" />
                                    <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Selecione...</option>
                                        <option value="M" {{ old('gender', $patient->gender) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('gender', $patient->gender) == 'F' ? 'selected' : '' }}>Feminino</option>
                                        <option value="O" {{ old('gender', $patient->gender) == 'O' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>

                                <div>
                                    <x-input-label for="marital_status" value="Estado Civil" />
                                    <select id="marital_status" name="marital_status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Selecione...</option>
                                        <option value="Solteiro" {{ old('marital_status', $patient->marital_status) == 'Solteiro' ? 'selected' : '' }}>Solteiro</option>
                                        <option value="Casado" {{ old('marital_status', $patient->marital_status) == 'Casado' ? 'selected' : '' }}>Casado</option>
                                        <option value="Divorciado" {{ old('marital_status', $patient->marital_status) == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                                        <option value="Viúvo" {{ old('marital_status', $patient->marital_status) == 'Viúvo' ? 'selected' : '' }}>Viúvo</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('marital_status')" />
                                </div>
                            </div>

                            <!-- Contato e Endereço -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Contato e Endereço</h3>

                                <div>
                                    <x-input-label for="phone" value="Telefone" />
                                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $patient->phone)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>

                                <div>
                                    <x-input-label for="email" value="E-mail" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $patient->email)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-input-label for="address" value="Endereço" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $patient->address)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>

                                <div>
                                    <x-input-label for="city" value="Cidade" />
                                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $patient->city)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                                </div>

                                <div>
                                    <x-input-label for="state" value="Estado" />
                                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $patient->state)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('state')" />
                                </div>

                                <div>
                                    <x-input-label for="zip_code" value="CEP" />
                                    <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', $patient->zip_code)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
                                </div>
                            </div>

                            <!-- Dados de Saúde -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Dados de Saúde</h3>

                                <div>
                                    <x-input-label for="health_insurance" value="Convênio" />
                                    <x-text-input id="health_insurance" name="health_insurance" type="text" class="mt-1 block w-full" :value="old('health_insurance', $patient->health_insurance)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('health_insurance')" />
                                </div>

                                <div>
                                    <x-input-label for="health_insurance_number" value="Número do Convênio" />
                                    <x-text-input id="health_insurance_number" name="health_insurance_number" type="text" class="mt-1 block w-full" :value="old('health_insurance_number', $patient->health_insurance_number)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('health_insurance_number')" />
                                </div>

                                <div>
                                    <x-input-label for="allergies" value="Alergias" />
                                    <textarea id="allergies" name="allergies" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('allergies', $patient->allergies) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('allergies')" />
                                </div>

                                <div>
                                    <x-input-label for="chronic_diseases" value="Doenças Crônicas" />
                                    <textarea id="chronic_diseases" name="chronic_diseases" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('chronic_diseases')" />
                                </div>

                                <div>
                                    <x-input-label for="medications" value="Medicações em Uso" />
                                    <textarea id="medications" name="medications" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('medications', $patient->medications) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('medications')" />
                                </div>

                                <div>
                                    <x-input-label for="notes" value="Observações" />
                                    <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes', $patient->notes) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('pacientes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Voltar
                            </a>
                            <x-primary-button>Salvar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
