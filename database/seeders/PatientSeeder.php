<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            [
                'name' => 'João Silva',
                'cpf' => '123.456.789-00',
                'rg' => '12.345.678-9',
                'birth_date' => '1980-05-15',
                'gender' => 'M',
                'marital_status' => 'Casado',
                'phone' => '(11) 98765-4321',
                'email' => 'joao.silva@email.com',
                'address' => 'Rua das Flores, 123',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01234-567',
                'health_insurance' => 'Unimed',
                'health_insurance_number' => '123456789',
                'allergies' => 'Penicilina',
                'chronic_diseases' => 'Hipertensão',
                'medications' => 'Losartana 50mg',
                'notes' => 'Paciente com histórico de pressão alta',
            ],
            [
                'name' => 'Maria Santos',
                'cpf' => '987.654.321-00',
                'rg' => '98.765.432-1',
                'birth_date' => '1992-08-20',
                'gender' => 'F',
                'marital_status' => 'Solteira',
                'phone' => '(11) 91234-5678',
                'email' => 'maria.santos@email.com',
                'address' => 'Avenida Principal, 456',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '04567-890',
                'health_insurance' => 'Amil',
                'health_insurance_number' => '987654321',
                'allergies' => 'Nenhuma',
                'chronic_diseases' => 'Diabetes tipo 2',
                'medications' => 'Metformina 850mg',
                'notes' => 'Paciente com diabetes controlada',
            ],
            [
                'name' => 'Pedro Oliveira',
                'cpf' => '456.789.123-00',
                'rg' => '45.678.912-3',
                'birth_date' => '1975-03-10',
                'gender' => 'M',
                'marital_status' => 'Divorciado',
                'phone' => '(11) 94567-8901',
                'email' => 'pedro.oliveira@email.com',
                'address' => 'Rua dos Pinheiros, 789',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '05678-901',
                'health_insurance' => 'Particular',
                'health_insurance_number' => null,
                'allergies' => 'Dipirona',
                'chronic_diseases' => 'Nenhuma',
                'medications' => 'Nenhuma',
                'notes' => 'Paciente com alergia a dipirona',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
