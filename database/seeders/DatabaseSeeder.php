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


        User::create([
            'name' => 'Administrador',
            'cpf' => '191.097.718-75',
            'email' => 'ser-moreira@hotmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
