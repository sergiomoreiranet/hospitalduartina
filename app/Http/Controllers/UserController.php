<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $query = User::query();

        // Filtro de busca
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por setor
        if (request('sector')) {
            $query->where('sector_id', request('sector'));
        }

        // Filtro por tipo
        if (request('type')) {
            switch (request('type')) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'sector_admin':
                    $query->where('is_sector_admin', true);
                    break;
                case 'regular':
                    $query->where('is_admin', false)
                          ->where('is_sector_admin', false);
                    break;
            }
        }

        $users = $query->paginate(15);
        $sectors = Sector::withCount(['administrators', 'regularUsers'])->get();

        return view('users.index', compact('users', 'sectors'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $sectors = Sector::all();
        return view('users.create', compact('sectors'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de :max caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo usado por outro usuário.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
            'cpf.max' => 'O CPF não pode ter mais de :max caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'user_type.required' => 'Selecione o tipo de usuário.',
            'user_type.in' => 'Tipo de usuário inválido.',
            'sector_id.required' => 'Selecione um setor para o usuário.',
            'sector_id.exists' => 'O setor selecionado é inválido.',
        ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cpf' => ['required', 'string', 'max:14', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:regular,admin,sector_admin'],
            'sector_id' => ['required', 'exists:sectors,id'],
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password),
            'is_admin' => $request->user_type === 'admin',
            'is_sector_admin' => $request->user_type === 'sector_admin',
            'sector_id' => $request->sector_id,
        ]);

        // Envia o e-mail de verificação
        $user->sendEmailVerificationNotification();

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $sectors = Sector::all();
        return view('users.edit', compact('user', 'sectors'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'cpf' => ['required', 'string', 'max:14', 'unique:users,cpf,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:regular,admin,sector_admin'],
            'sector_id' => ['required', 'exists:sectors,id'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'is_admin' => $request->user_type === 'admin',
            'is_sector_admin' => $request->user_type === 'sector_admin',
            'sector_id' => $request->sector_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Usuário removido com sucesso.');
    }

    public function export()
    {
        $query = User::query()
            ->with('sector'); // Eager loading do relacionamento sector

        // Aplicar os mesmos filtros do index
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (request('sector')) {
            $query->where('sector_id', request('sector'));
        }

        if (request('type')) {
            switch (request('type')) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'sector_admin':
                    $query->where('is_sector_admin', true);
                    break;
                case 'regular':
                    $query->where('is_admin', false)
                          ->where('is_sector_admin', false);
                    break;
            }
        }

        $users = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=usuarios.csv',
        ];

        $callback = function() use($users) {
            $file = fopen('php://output', 'w');
            // Adiciona BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho
            fputcsv($file, [
                'Nome',
                'CPF',
                'Email',
                'Setor',
                'Tipo'
            ], ';'); // Usando ; como delimitador para melhor compatibilidade com Excel

            // Dados
            foreach ($users as $user) {
                $type = 'Usuário Comum';
                if ($user->is_admin) {
                    $type = 'Administrador Geral';
                } elseif ($user->is_sector_admin) {
                    $type = 'Admin. de Setor';
                }

                fputcsv($file, [
                    $user->name,
                    $user->cpf,
                    $user->email,
                    $user->sector ? $user->sector->name : 'Sem setor',
                    $type
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $query = User::query()
            ->with('sector');

        // Aplicar os mesmos filtros do index
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (request('sector')) {
            $query->where('sector_id', request('sector'));
        }

        if (request('type')) {
            switch (request('type')) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'sector_admin':
                    $query->where('is_sector_admin', true);
                    break;
                case 'regular':
                    $query->where('is_admin', false)
                          ->where('is_sector_admin', false);
                    break;
            }
        }

        $users = $query->get();
        
        $pdf = PDF::loadView('users.pdf', compact('users'));
        $pdf->getDomPDF()->set_option('isPhpEnabled', true);
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('enable_php', true);
        
        return $pdf->download('usuarios.pdf');
    }
} 