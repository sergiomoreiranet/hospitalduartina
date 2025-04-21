<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SectorAdministratorController extends Controller
{
    /**
     * Display a listing of the administrators for a sector.
     */
    public function index(Sector $sector)
    {
        $administrators = $sector->administrators()->paginate(10);
        $availableAdmins = User::where('is_sector_admin', true)
            ->whereNull('sector_id')
            ->get();
        return view('sectors.administrators', compact('sector', 'administrators', 'availableAdmins'));
    }

    /**
     * Show the form for creating a new administrator for a sector.
     */
    public function create(Sector $sector)
    {
        return view('sectors.create-administrator', compact('sector'));
    }

    /**
     * Store a newly created administrator for a sector.
     */
    public function store(Request $request, Sector $sector)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:14', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_sector_admin' => true,
            'sector_id' => $sector->id,
        ]);

        return redirect()->route('sectors.administrators', $sector)
            ->with('success', 'Administrador do setor criado com sucesso!');
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(Sector $sector, User $administrator)
    {
        return view('sectors.edit-administrator', compact('sector', 'administrator'));
    }

    /**
     * Update the specified administrator in storage.
     */
    public function update(Request $request, Sector $sector, User $administrator)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $administrator->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $administrator->update($data);

        return redirect()->route('sectors.administrators', $sector)
            ->with('success', 'Administrador do setor atualizado com sucesso!');
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(Sector $sector, User $administrator)
    {
        $administrator->update([
            'is_sector_admin' => false,
            'sector_id' => null,
        ]);

        return redirect()->route('sectors.administrators', $sector)
            ->with('success', 'Administrador do setor removido com sucesso!');
    }
}
