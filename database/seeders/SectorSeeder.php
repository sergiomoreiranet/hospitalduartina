<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            [
                'name' => 'Administração',
                'description' => 'Setor responsável pela gestão administrativa do hospital',
                'is_active' => true
            ],
            [
                'name' => 'Enfermagem',
                'description' => 'Setor responsável pelos cuidados de enfermagem',
                'is_active' => true
            ],
            [
                'name' => 'Médicos',
                'description' => 'Setor responsável pelos médicos e atendimentos',
                'is_active' => true
            ],
            [
                'name' => 'Farmácia',
                'description' => 'Setor responsável pelo controle e distribuição de medicamentos',
                'is_active' => true
            ],
            [
                'name' => 'Laboratório',
                'description' => 'Setor responsável pelos exames laboratoriais',
                'is_active' => true
            ],
            [
                'name' => 'Recursos Humanos',
                'description' => 'Setor responsável pela gestão de pessoas',
                'is_active' => true
            ],
            [
                'name' => 'TI',
                'description' => 'Setor responsável pela infraestrutura tecnológica',
                'is_active' => true
            ],
            [
                'name' => 'Manutenção',
                'description' => 'Setor responsável pela manutenção predial e equipamentos',
                'is_active' => true
            ],
            [
                'name' => 'Limpeza',
                'description' => 'Setor responsável pela higienização do hospital',
                'is_active' => true
            ],
            [
                'name' => 'Nutrição',
                'description' => 'Setor responsável pela alimentação',
                'is_active' => true
            ]
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}
