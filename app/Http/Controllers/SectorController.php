<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function destroy(Sector $sector, Request $request)
    {
        \Log::info('Método destroy chamado', [
            'sector_id' => $sector->id,
            'request_method' => $request->method(),
            'request_all' => $request->all(),
            'is_admin' => auth()->user()->is_admin
        ]);

        if (!auth()->user()->is_admin) {
            return redirect()->route('sectors.index')
                ->with('error', 'Apenas administradores podem desativar setores.');
        }

        try {
            $reason = $request->input('deactivation_reason');
            if (empty($reason)) {
                \Log::warning('Tentativa de desativação sem motivo', [
                    'sector_id' => $sector->id,
                    'user_id' => auth()->id()
                ]);
                return redirect()->back()
                    ->with('error', 'O motivo da desativação é obrigatório.');
            }

            \Log::info('Iniciando desativação do setor', [
                'sector_id' => $sector->id,
                'request_all' => $request->all(),
                'deactivation_reason' => $reason,
                'user_id' => auth()->id()
            ]);

            DB::transaction(function () use ($sector, $reason) {
                $userId = auth()->id();

                \Log::info('Dentro da transaction', [
                    'sector_id' => $sector->id,
                    'user_id' => $userId,
                    'reason' => $reason,
                    'sector_before' => $sector->toArray()
                ]);
                
                // Desativa o setor
                $updated = $sector->update([
                    'is_active' => false,
                    'deactivated_by' => $userId,
                    'deactivated_at' => now(),
                    'deactivation_reason' => $reason
                ]);

                \Log::info('Setor atualizado', [
                    'success' => $updated,
                    'sector_after' => $sector->fresh()->toArray()
                ]);

                // Desativa todos os usuários do setor
                $usersUpdated = $sector->users()->update([
                    'is_active' => false,
                    'deactivated_by' => $userId,
                    'deactivated_at' => now(),
                    'deactivation_reason' => 'Setor desativado: ' . $reason
                ]);

                \Log::info('Usuários atualizados', [
                    'users_affected' => $usersUpdated,
                    'sector_id' => $sector->id
                ]);
            });

            // Mantém a página atual no redirecionamento
            $page = $request->input('current_page', 1);
            
            \Log::info('Desativação concluída com sucesso', [
                'sector_id' => $sector->id,
                'redirect_page' => $page
            ]);

            return redirect()->route('sectors.index', ['page' => $page])
                ->with('success', 'Setor desativado com sucesso.');
        } catch (\Exception $e) {
            \Log::error('Erro ao desativar setor', [
                'sector_id' => $sector->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_all' => $request->all()
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
} 