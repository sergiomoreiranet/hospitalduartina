<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário admin apenas se não existir
        if (!User::where('cpf', env('ADMIN_CPF'))->exists()) {
            User::create([
                'name' => env('ADMIN_NAME'),
                'cpf' => env('ADMIN_CPF'),
                'email' => env('ADMIN_EMAIL'),
                'password' => Hash::make(env('ADMIN_PASSWORD')),
            ]);
        }

        $this->call([
            SectorSeeder::class,
            PatientFlowSeeder::class,
        ]);
    }
}
