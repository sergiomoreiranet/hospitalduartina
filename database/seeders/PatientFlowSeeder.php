<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\PatientFlow;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientFlowSeeder extends Seeder
{
    public function run(): void
    {
        // Criar alguns setores
        $sectors = [
            ['name' => 'Recepção', 'description' => 'Primeiro atendimento e triagem'],
            ['name' => 'Consultório Médico', 'description' => 'Atendimento médico'],
            ['name' => 'Enfermaria', 'description' => 'Cuidados de enfermagem'],
            ['name' => 'Laboratório', 'description' => 'Exames laboratoriais'],
            ['name' => 'Radiologia', 'description' => 'Exames de imagem'],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }

        // Criar alguns pacientes
        $patients = [
            [
                'name' => 'João Silva',
                'cpf' => '111.222.333-44',
                'rg' => '12.345.678-9',
                'birth_date' => '1980-05-15',
                'gender' => 'M',
                'marital_status' => 'Casado',
                'phone' => '(11) 99999-9999',
                'email' => 'joao@email.com',
                'address' => 'Rua A, 123',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01234-567',
                'health_insurance' => 'Unimed',
                'health_insurance_number' => '123456789',
                'allergies' => 'Penicilina',
                'chronic_diseases' => 'Hipertensão',
                'medications' => 'Losartana 50mg',
                'notes' => 'Paciente com histórico de pressão alta',
                'is_active' => true,
            ],
            [
                'name' => 'Maria Santos',
                'cpf' => '222.333.444-55',
                'rg' => '23.456.789-0',
                'birth_date' => '1992-08-20',
                'gender' => 'F',
                'marital_status' => 'Solteira',
                'phone' => '(11) 98888-8888',
                'email' => 'maria@email.com',
                'address' => 'Rua B, 456',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '04567-890',
                'health_insurance' => 'Amil',
                'health_insurance_number' => '987654321',
                'allergies' => 'Nenhuma',
                'chronic_diseases' => 'Nenhuma',
                'medications' => 'Nenhuma',
                'notes' => 'Paciente saudável',
                'is_active' => true,
            ],
            [
                'name' => 'Pedro Oliveira',
                'cpf' => '333.444.555-66',
                'rg' => '34.567.890-1',
                'birth_date' => '1975-12-10',
                'gender' => 'M',
                'marital_status' => 'Divorciado',
                'phone' => '(11) 97777-7777',
                'email' => 'pedro@email.com',
                'address' => 'Rua C, 789',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '07890-123',
                'health_insurance' => 'SulAmérica',
                'health_insurance_number' => '456789123',
                'allergies' => 'Dipirona',
                'chronic_diseases' => 'Diabetes',
                'medications' => 'Metformina 850mg',
                'notes' => 'Paciente diabético tipo 2',
                'is_active' => true,
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }

        // Criar alguns fluxos de pacientes
        $flows = [
            [
                'patient_id' => 1,
                'sector_id' => 1,
                'status' => 'aguardando',
                'check_in' => now(),
                'is_priority' => true,
                'observations' => 'Paciente com dor de cabeça intensa',
                'queue_position' => 1,
            ],
            [
                'patient_id' => 2,
                'sector_id' => 2,
                'status' => 'em_atendimento',
                'check_in' => now()->subMinutes(30),
                'is_priority' => false,
                'observations' => 'Consulta de rotina',
                'queue_position' => 1,
            ],
            [
                'patient_id' => 3,
                'sector_id' => 3,
                'status' => 'concluido',
                'check_in' => now()->subHours(2),
                'check_out' => now()->subHour(),
                'is_priority' => false,
                'observations' => 'Aplicação de medicamento',
                'conclusion' => 'Medicamento aplicado com sucesso',
                'queue_position' => 1,
            ],
        ];

        foreach ($flows as $flow) {
            PatientFlow::create($flow);
        }
    }
} 