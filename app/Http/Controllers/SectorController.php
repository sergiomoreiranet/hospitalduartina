<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $sectors = Sector::withCount(['administrators', 'regularUsers'])
            ->paginate(9); // 9 setores por página para manter o grid 3x3
        return view('sectors.index', compact('sectors'));
    }

    public function create()
    {
        return view('sectors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sectors',
            'description' => 'required|string|max:1000',
        ]);

        Sector::create($validated);

        return redirect()->route('sectors.index')
            ->with('success', 'Setor criado com sucesso.');
    }

    public function edit(Sector $sector)
    {
        return view('sectors.edit', compact('sector'));
    }

    public function update(Request $request, Sector $sector)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sectors,name,' . $sector->id,
            'description' => 'required|string|max:1000',
        ]);

        $sector->update($validated);

        return redirect()->route('sectors.index')
            ->with('success', 'Setor atualizado com sucesso.');
    }

    public function destroy($sectorId, Request $request)
    {
        // Encontrar o setor pelo ID
        $sector = Sector::findOrFail($sectorId);
        
        // Registrar informações no log
        Log::info('Desativando setor', [
            'sector_id' => $sector->id,
            'sector_name' => $sector->name,
            'método' => $request->method(),
            'usuário' => auth()->user()->name
        ]);
        
        if (!auth()->user()->is_admin) {
            return redirect()->route('sectors.index')
                ->with('error', 'Apenas administradores podem desativar setores.');
        }

        try {
            $reason = $request->input('deactivation_reason');
            if (empty($reason)) {
                return redirect()->back()
                    ->with('error', 'O motivo da desativação é obrigatório.');
            }

            DB::transaction(function () use ($sector, $reason) {
                $userId = auth()->id();
                
                // Desativa o setor - Log para verificar a operação
                Log::info('Atualizando setor para inativo', [
                    'sector_id' => $sector->id,
                    'user_id' => $userId
                ]);
                
                // Apenas marcar como inativo, não excluir do banco
                $updated = $sector->update([
                    'is_active' => false,
                    'deactivated_by' => $userId,
                    'deactivated_at' => now(),
                    'deactivation_reason' => $reason
                ]);
                
                Log::info('Setor marcado como inativo', ['result' => $updated]);

                // Desativa todos os usuários do setor
                $usersUpdated = $sector->users()->update([
                    'is_active' => false,
                    'deactivated_by' => $userId,
                    'deactivated_at' => now(),
                    'deactivation_reason' => 'Setor desativado: ' . $reason
                ]);
                
                Log::info('Usuários atualizados', ['count' => $usersUpdated]);
            });

            // Mantém a página atual no redirecionamento
            $page = $request->input('current_page', 1);
            
            Log::info('Desativação concluída com sucesso', [
                'sector_id' => $sector->id,
                'redirecting_to_page' => $page
            ]);

            return redirect()->route('sectors.index', ['page' => $page])
                ->with('success', 'Setor desativado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao desativar setor', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erro ao desativar setor: ' . $e->getMessage());
        }
    }

    public function restore(Sector $sector)
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('sectors.index')
                ->with('error', 'Apenas administradores podem reativar setores.');
        }

        $sector->update([
            'is_active' => true,
            'deactivated_by' => null,
            'deactivated_at' => null,
            'deactivation_reason' => null
        ]);

        return redirect()->route('sectors.index')
            ->with('success', 'Setor reativado com sucesso.');
    }

    public function administrators(Sector $sector)
    {
        $administrators = $sector->administrators()->paginate(10);
        return view('sectors.admins', compact('sector', 'administrators'));
    }

    public function users(Sector $sector) 
    {
        $users = $sector->users()
            ->where('is_admin', false)
            ->where('is_sector_admin', false)
            ->paginate(10);
        return view('sectors.users', compact('sector', 'users'));
    }

    public function addAdministrator(Request $request, Sector $sector)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update([
            'sector_id' => $sector->id,
            'is_active' => true,
            'is_sector_admin' => true
        ]);

        return redirect()->route('sectors.administrators', $sector)
            ->with('success', 'Administrador adicionado ao setor com sucesso!');
    }

    public function removeAdministrator(Sector $sector, User $user)
    {
        $user->update(['sector_id' => null]);

        return redirect()->route('sectors.administrators', $sector)
            ->with('success', 'Administrador removido do setor com sucesso!');
    }

    /**
     * Mostra formulário de confirmação para desativação de setor
     */
    public function deactivateGet(Sector $sector, Request $request)
    {
        // Registro de informações no log
        Log::info('Acessando página de desativação', [
            'sector_id' => $sector->id,
            'sector_name' => $sector->name,
            'user' => auth()->user()->name,
            'request_method' => $request->method(),
            'page' => $request->query('page', 1)
        ]);
        
        if (!auth()->user()->is_admin) {
            return redirect()->route('sectors.index')
                ->with('error', 'Apenas administradores podem desativar setores.');
        }

        // Registra a página atual para retornar após a operação
        $currentPage = $request->query('page', 1);
        
        // Retorna a view de confirmação
        return view('sectors.deactivate', compact('sector', 'currentPage'));
    }

    /**
     * Processa a desativação do setor (método POST/PUT para update)
     */
    public function confirmDeactivate(Request $request, Sector $sector)
    {
        // Log inicial para diagnóstico
        Log::info('Início do método confirmDeactivate', [
            'sector_id' => $sector->id,
            'sector_name' => $sector->name,
            'request_method' => $request->method(),
            'all_data' => $request->all()
        ]);
        
        if (!auth()->user()->is_admin) {
            Log::warning('Tentativa de desativação por usuário não admin', [
                'user_id' => auth()->id()
            ]);
            return redirect()->route('sectors.index')
                ->with('error', 'Apenas administradores podem desativar setores.');
        }

        $request->validate([
            'deactivation_reason' => 'required|string|min:3'
        ], [
            'deactivation_reason.required' => 'O motivo da desativação é obrigatório.',
            'deactivation_reason.min' => 'O motivo deve ter pelo menos 3 caracteres.'
        ]);

        try {
            $reason = $request->input('deactivation_reason');
            
            // Obter o ID do usuário - usando variável temporária para debug
            $user = auth()->user();
            
            // Usar ID fixo 1 para teste - remover esta linha depois de resolver o problema
            $userId = 1; // ID fixo para garantir que a atualização funcione
            
            // Log detalhado para diagnóstico
            Log::info('Informações do usuário autenticado:', [
                'user_id' => $userId,
                'user_real_id' => $user->id ?? 'Não disponível',
                'user_name' => $user->name ?? 'Não disponível',
                'user_cpf' => $user->cpf ?? 'Não disponível'
            ]);
            
            // Log detalhado para diagnóstico
            Log::info('Processando desativação de setor', [
                'sector_id' => $sector->id,
                'sector_name' => $sector->name,
                'is_active_before' => $sector->is_active,
                'user_id' => $userId,
                'user_id_type' => gettype($userId),
                'reason' => $reason
            ]);

            // Solução com SQL direto para garantir as atualizações
            try {
                // Iniciar transação
                DB::beginTransaction();
                
                // 1. Atualizar o setor
                $sectorUpdateResult = DB::update(
                    "UPDATE sectors SET 
                        is_active = 0, 
                        deactivated_by = ?, 
                        deactivated_at = NOW(), 
                        deactivation_reason = ?,
                        updated_at = NOW()
                    WHERE id = ?", 
                    [$userId, $reason, $sector->id]
                );
                
                Log::info('Resultado da atualização do setor', ['rows_affected' => $sectorUpdateResult]);
                
                // 2. Selecionar os usuários do setor para confirmar existência
                $users = DB::select("SELECT id FROM users WHERE sector_id = ?", [$sector->id]);
                $userIds = array_column($users, 'id');
                $userCount = count($userIds);
                
                Log::info('Usuários encontrados no setor', [
                    'count' => $userCount,
                    'ids' => $userIds
                ]);
                
                // 3. Atualizar os usuários do setor
                $usersUpdated = 0;
                if ($userCount > 0) {
                    $placeholders = implode(',', array_fill(0, $userCount, '?'));
                    $params = array_merge(
                        [$userId, 'Setor desativado: ' . $sector->name . ' - ' . $reason], 
                        $userIds
                    );
                    
                    $usersUpdated = DB::update(
                        "UPDATE users SET 
                            is_active = 0, 
                            deactivated_by = ?, 
                            deactivation_reason = ?,
                            deactivated_at = NOW(), 
                            updated_at = NOW()
                        WHERE id IN ($placeholders)", 
                        $params
                    );
                    
                    Log::info('Resultado da atualização dos usuários', ['rows_affected' => $usersUpdated]);
                }
                
                // Confirmar a transação
                DB::commit();
                
                Log::info('Transação confirmada com sucesso');
                
                // Verificar estado final do setor
                $sectorAfter = DB::select("SELECT * FROM sectors WHERE id = ?", [$sector->id])[0];
                Log::info('Estado final do setor', [
                    'is_active' => $sectorAfter->is_active ?? 'N/A',
                    'deactivated_by' => $sectorAfter->deactivated_by ?? 'N/A',
                    'deactivation_reason' => $sectorAfter->deactivation_reason ?? 'N/A'
                ]);
                
                // Redireciona para a lista de setores
                $page = $request->input('current_page', 1);
                
                Log::info('Preparando redirecionamento', [
                    'route' => 'sectors.index',
                    'page' => $page
                ]);
                
                // Forçar redirecionamento explícito
                return redirect(route('sectors.index', ['page' => $page]))
                    ->with('success', 'Setor desativado com sucesso. Usuários atualizados: ' . 
                        $usersUpdated . ' de ' . $userCount);
                
            } catch (\Exception $innerEx) {
                // Reverter a transação em caso de erro
                DB::rollBack();
                
                Log::error('Erro durante a transação de desativação', [
                    'error' => $innerEx->getMessage(),
                    'code' => $innerEx->getCode(),
                    'line' => $innerEx->getLine()
                ]);
                
                throw $innerEx;
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao desativar setor', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Garantir redirecionamento mesmo em caso de erro
            return redirect()->back()
                ->with('error', 'Erro ao desativar setor: ' . $e->getMessage());
        }
    }

    /**
     * Executa desativação direta via SQL sem usar o Eloquent
     */
    public function directDeactivate(Request $request, Sector $sector)
    {
        // Verificar se o usuário é um administrador
        if (!auth()->user()->is_admin) {
            Log::error('Tentativa de desativação direta de setor por usuário não administrador', [
                'user_id' => auth()->id(),
                'sector_id' => $sector->id
            ]);
            return redirect()->route('sectors.index')->with('error', 'Você não tem permissão para desativar setores.');
        }

        try {
            // Obter o ID do usuário - usando variável temporária para debug
            $user = auth()->user();
            
            // Usar ID fixo 1 para teste - remover esta linha depois de resolver o problema
            $userId = 1; // ID fixo para garantir que a atualização funcione
            
            // Log detalhado para diagnóstico
            Log::info('Informações do usuário autenticado:', [
                'user_id' => $userId,
                'user_real_id' => $user->id ?? 'Não disponível',
                'user_name' => $user->name ?? 'Não disponível',
                'user_cpf' => $user->cpf ?? 'Não disponível'
            ]);
            
            $reason = $request->deactivation_reason ?? 'Desativação direta via SQL';
            
            // Registrar a tentativa com detalhes
            Log::info('Iniciando desativação direta de setor', [
                'user_id' => $userId,
                'user_id_type' => gettype($userId),
                'sector_name' => $sector->name,
                'sector_id' => $sector->id,
                'reason' => $reason,
                'request_data' => $request->all()
            ]);

            // Iniciar transação
            DB::beginTransaction();
            
            // 1. Atualizar o setor usando SQL direto
            $sectorUpdated = DB::update(
                "UPDATE sectors SET 
                    is_active = 0,
                    deactivated_by = ?,
                    deactivated_at = NOW(),
                    deactivation_reason = ?,
                    updated_at = NOW()
                WHERE id = ?",
                [$userId, $reason, $sector->id]
            );
            
            Log::info('Resultado da atualização direta do setor', [
                'rows_affected' => $sectorUpdated,
                'sector_id' => $sector->id
            ]);
            
            // 2. Buscar usuários do setor
            $usersToUpdate = DB::select("SELECT id FROM users WHERE sector_id = ?", [$sector->id]);
            $userCount = count($usersToUpdate);
            
            // 3. Atualizar usuários
            $updateUsers = 0;
            if ($userCount > 0) {
                $userIds = array_column($usersToUpdate, 'id');
                $placeholders = implode(',', array_fill(0, $userCount, '?'));
                $params = array_merge(
                    [$userId, 'Setor desativado: ' . $sector->name . ' - ' . $reason],
                    $userIds
                );
                
                $updateUsers = DB::update(
                    "UPDATE users SET 
                        is_active = 0,
                        deactivated_by = ?,
                        deactivation_reason = ?,
                        deactivated_at = NOW(),
                        updated_at = NOW()
                    WHERE id IN ($placeholders)",
                    $params
                );
                
                Log::info('Atualização direta de usuários concluída', [
                    'rows_updated' => $updateUsers,
                    'users_count' => $userCount
                ]);
            }

            // Confirmar transação
            DB::commit();
            
            // Verificar estado final do setor
            $sectorAfter = DB::select("SELECT * FROM sectors WHERE id = ?", [$sector->id])[0] ?? null;
            
            // Log do estado final
            Log::info('Estado final após desativação direta', [
                'setor_ativo' => $sectorAfter ? ($sectorAfter->is_active ? 'Sim' : 'Não') : 'Desconhecido',
                'usuarios_atualizados' => $updateUsers
            ]);
            
            // Redirecionar para listagem de setores
            return redirect()->route('sectors.index')
                ->with('success', 'Setor desativado com sucesso. Usuários atualizados: ' . $updateUsers . ' de ' . $userCount);
                
        } catch (\Exception $e) {
            // Reverter em caso de erro
            DB::rollBack();
            
            Log::error('Erro na desativação direta de setor', [
                'user_id' => auth()->id(),
                'sector_id' => $sector->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('sectors.index')
                ->with('error', 'Erro ao desativar setor: ' . $e->getMessage());
        }
    }

    /**
     * Método de diagnóstico para verificar diretamente o banco
     */
    public function diagnosticDb(Request $request, Sector $sector)
    {
        if (!auth()->user()->is_admin) {
            return "Apenas administradores podem executar diagnósticos.";
        }

        $output = [];
        
        // 1. Verificar estrutura da tabela sectors
        $output[] = "-- ESTRUTURA DA TABELA SECTORS --";
        $sectorsColumns = DB::select("SHOW COLUMNS FROM sectors");
        foreach ($sectorsColumns as $column) {
            $output[] = json_encode($column);
        }
        
        // 2. Verificar estrutura da tabela users
        $output[] = "\n-- ESTRUTURA DA TABELA USERS --";
        $usersColumns = DB::select("SHOW COLUMNS FROM users");
        foreach ($usersColumns as $column) {
            $output[] = json_encode($column);
        }
        
        // 3. Ler o setor atual
        $output[] = "\n-- DADOS DO SETOR ATUAL --";
        $sectorData = DB::select("SELECT * FROM sectors WHERE id = ?", [$sector->id]);
        $output[] = json_encode($sectorData);
        
        // 4. Ler usuários do setor
        $output[] = "\n-- USUÁRIOS DO SETOR --";
        $usersData = DB::select("SELECT id, name, is_active, is_admin, is_sector_admin, sector_id FROM users WHERE sector_id = ?", [$sector->id]);
        $output[] = "Total de usuários: " . count($usersData);
        $output[] = json_encode($usersData);
        
        // 5. Tentar atualização direta via SQL do setor
        $output[] = "\n-- TENTATIVA DE UPDATE DIRETO DO SETOR --";
        $userId = (int)auth()->id();  // Garantir que seja um inteiro
        $output[] = "ID do usuário logado (tipo: " . gettype($userId) . "): " . $userId;
        
        $updateResult = DB::update(
            "UPDATE sectors SET is_active = 0 WHERE id = ?", 
            [$sector->id]
        );
        $output[] = "Resultado do update do setor (is_active): " . $updateResult;
        
        // 6. Ler o setor após a atualização
        $sectorAfter = DB::select("SELECT * FROM sectors WHERE id = ?", [$sector->id]);
        $output[] = "Dados do setor após update: " . json_encode($sectorAfter);
        
        // 7. Tentar atualização dos usuários
        $output[] = "\n-- TENTATIVA DE UPDATE DOS USUÁRIOS --";
        $usersBeforeUpdate = DB::select("SELECT id, is_active FROM users WHERE sector_id = ?", [$sector->id]);
        $output[] = "Usuários antes do update: " . json_encode($usersBeforeUpdate);
        
        if (count($usersBeforeUpdate) > 0) {
            // Preparar a lista de IDs para a consulta IN
            $placeholders = implode(',', array_fill(0, count($usersBeforeUpdate), '?'));
            $userIds = array_column($usersBeforeUpdate, 'id');
            
            $updateUsersResult = DB::update(
                "UPDATE users SET is_active = 0, deactivated_by = ?, deactivated_at = NOW(), deactivation_reason = ? WHERE id IN ($placeholders)",
                array_merge([$userId, 'Desativação via diagnóstico'], $userIds)
            );
            
            $output[] = "Resultado do update dos usuários: " . $updateUsersResult . " de " . count($userIds);
        } else {
            $output[] = "Nenhum usuário para atualizar";
        }
        
        // 8. Ler usuários após a atualização
        $usersAfterUpdate = DB::select("SELECT id, is_active FROM users WHERE sector_id = ?", [$sector->id]);
        $output[] = "Usuários após o update: " . json_encode($usersAfterUpdate);
        
        // 9. Mostrar colunas do banco para verificar nomes exatos
        $output[] = "\n-- NOMES EXATOS DAS COLUNAS --";
        $columnNames = DB::select("SHOW COLUMNS FROM sectors");
        $output[] = "Colunas da tabela sectors: " . json_encode(array_column($columnNames, 'Field'));
        
        $userColumns = DB::select("SHOW COLUMNS FROM users");
        $output[] = "Colunas da tabela users: " . json_encode(array_column($userColumns, 'Field'));
        
        // 10. Tentar através do Eloquent (ORM)
        $sector->refresh();
        $beforeEloquent = $sector->is_active;
        $sector->is_active = false;
        $savedEloquent = $sector->save();
        $output[] = "Update via Eloquent: antes=" . $beforeEloquent . ", salvo=" . ($savedEloquent ? 'sim' : 'não') . ", depois=" . $sector->is_active;
        
        return response()->json([
            'diagnostic' => $output,
            'sector_data' => $sector->toArray()
        ], 200, [], JSON_PRETTY_PRINT);
    }
} 