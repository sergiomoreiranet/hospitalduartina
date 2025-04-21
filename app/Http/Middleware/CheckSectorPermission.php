<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSectorPermission
{
    public function handle(Request $request, Closure $next, $moduleCode)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Administradores do sistema têm acesso total
        if ($user->is_admin) {
            return $next($request);
        }

        // Verifica se o usuário tem permissão para o módulo
        $permission = $user->sector->permissions()
            ->where('module_code', $moduleCode)
            ->first();

        if (!$permission || !$permission->can_view) {
            return redirect()->back()->with('error', 'Você não tem permissão para acessar este módulo.');
        }

        return $next($request);
    }
} 