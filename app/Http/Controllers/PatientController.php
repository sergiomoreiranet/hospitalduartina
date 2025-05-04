<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = Patient::where('is_active', true)
            ->orderBy('name')
            ->paginate(10);

        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:patients,cpf',
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,O',
            'marital_status' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:9',
            'health_insurance' => 'nullable|string|max:100',
            'health_insurance_number' => 'nullable|string|max:50',
            'allergies' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'medications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $patient = Patient::create($validated);

            DB::commit();

            return redirect()
                ->route('patients.show', $patient)
                ->with('success', 'Paciente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar paciente. Por favor, tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $paciente)
    {
        $paciente->load([
            'flows' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'sector',
            'currentFlow.sector',
            'lastFlow.sector'
        ]);

        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('pacientes.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:patients,cpf,' . $patient->id,
            'rg' => 'nullable|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,O',
            'marital_status' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:9',
            'health_insurance' => 'nullable|string|max:100',
            'health_insurance_number' => 'nullable|string|max:50',
            'allergies' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'medications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $patient->update($validated);

            DB::commit();

            return redirect()
                ->route('patients.show', $patient)
                ->with('success', 'Paciente atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar paciente. Por favor, tente novamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        try {
            DB::beginTransaction();

            $patient->update(['active' => false]);

            DB::commit();

            return redirect()
                ->route('patients.index')
                ->with('success', 'Paciente desativado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erro ao desativar paciente. Por favor, tente novamente.');
        }
    }
}
