<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o usuário administrador com as permissões corretas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cpf = env('ADMIN_CPF');
        
        if (!$cpf) {
            $this->error('CPF do administrador não encontrado no arquivo .env');
            return 1;
        }

        $user = User::where('cpf', $cpf)->first();

        if (!$user) {
            $this->error('Usuário administrador não encontrado');
            return 1;
        }

        $user->update([
            'is_admin' => true,
            'is_sector_admin' => true,
            'sector_id' => 1 // ID do setor de Administração
        ]);

        $this->info('Usuário administrador atualizado com sucesso!');
        $this->info('Nome: ' . $user->name);
        $this->info('CPF: ' . $user->cpf);
        $this->info('Email: ' . $user->email);
        $this->info('É administrador geral: ' . ($user->is_admin ? 'Sim' : 'Não'));
        $this->info('É administrador de setor: ' . ($user->is_sector_admin ? 'Sim' : 'Não'));
        $this->info('Setor: Administração');

        return 0;
    }
}
