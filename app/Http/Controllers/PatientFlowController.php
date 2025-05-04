<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientFlow;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PatientFlow::with(['patient', 'sector']);

        if ($request->filled('sector')) {
            $query->where('sector_id', $request->sector);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $patientFlows = $query->orderBy('created_at', 'desc')->paginate(10);
        $sectors = Sector::all();

        return view('patient-flows.index', compact('patientFlows', 'sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $sectors = Sector::where('is_active', true)->orderBy('name')->get();
        
        return view('patient-flows.create', compact('patients', 'sectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'sector_id' => 'required|exists:sectors,id',
            'is_priority' => 'boolean',
            'observations' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Verifica se o paciente já está em algum setor
            $currentFlow = PatientFlow::where('patient_id', $validated['patient_id'])
                ->whereNull('check_out')
                ->first();

            if ($currentFlow) {
                return back()
                    ->withInput()
                    ->with('error', 'Este paciente já está em atendimento em outro setor.');
            }

            // Obtém a última posição na fila do setor
            $lastPosition = PatientFlow::where('sector_id', $validated['sector_id'])
                ->whereNull('check_out')
                ->max('queue_position') ?? 0;

            // Cria o novo registro de fluxo
            $flow = PatientFlow::create([
                'patient_id' => $validated['patient_id'],
                'sector_id' => $validated['sector_id'],
                'user_id' => auth()->id(),
                'status' => 'aguardando',
                'check_in' => now(),
                'is_priority' => $validated['is_priority'] ?? false,
                'observations' => $validated['observations'] ?? null,
                'queue_position' => $lastPosition + 1,
            ]);

            // Atualiza as informações de internação do paciente
            $patient = Patient::find($validated['patient_id']);
            if ($patient) {
                $patient->update([
                    'status' => 'aguardando',
                    'sector_id' => $validated['sector_id'],
                    'admission_date' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('patient-flows.show', $flow)
                ->with('success', 'Paciente encaminhado para o setor com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao encaminhar paciente. Por favor, tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientFlow $patientFlow)
    {
        $patientFlow->load(['patient', 'sector', 'user', 'nextSector']);
        return view('patient-flows.show', compact('patientFlow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatientFlow $patientFlow)
    {
        // Busca todos os setores ativos, exceto o setor atual
        $sectors = Sector::where('is_active', true)
            ->where('id', '!=', $patientFlow->sector_id)
            ->orderBy('name')
            ->get();
            
        return view('patient-flows.edit', compact('patientFlow', 'sectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PatientFlow $patientFlow)
    {
        $validated = $request->validate([
            'status' => 'required|in:aguardando,em_atendimento,concluido,cancelado',
            'next_sector_id' => 'nullable|exists:sectors,id',
            'observations' => 'nullable|string',
            'conclusion' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Se o status for concluído, registra o check_out
            if ($validated['status'] === 'concluido') {
                $validated['check_out'] = now();
            }

            $patientFlow->update($validated);

            // Atualiza o paciente
            $patient = Patient::find($patientFlow->patient_id);
            if ($patient) {
                $updateData = [];

                // Se houver próximo setor
                if ($validated['next_sector_id']) {
                    $lastPosition = PatientFlow::where('sector_id', $validated['next_sector_id'])
                        ->whereNull('check_out')
                        ->max('queue_position') ?? 0;

                    // Cria o novo fluxo para o próximo setor
                    PatientFlow::create([
                        'patient_id' => $patientFlow->patient_id,
                        'sector_id' => $validated['next_sector_id'],
                        'user_id' => auth()->id(),
                        'status' => 'aguardando',
                        'check_in' => now(),
                        'queue_position' => $lastPosition + 1,
                    ]);

                    // Atualiza o paciente para o novo setor
                    $updateData = [
                        'status' => 'aguardando',
                        'sector_id' => $validated['next_sector_id']
                    ];
                } else {
                    // Se não houver próximo setor e o status for concluído
                    if ($validated['status'] === 'concluido') {
                        // Verifica se o paciente tem outros atendimentos ativos
                        $hasActiveFlows = PatientFlow::where('patient_id', $patient->id)
                            ->where('id', '!=', $patientFlow->id)
                            ->whereNull('check_out')
                            ->exists();

                        if (!$hasActiveFlows) {
                            $updateData = [
                                'status' => 'alta',
                                'sector_id' => null,
                                'bed' => null,
                                'discharge_date' => now()
                            ];
                        } else {
                            $updateData = [
                                'status' => 'aguardando',
                                'sector_id' => null,
                                'bed' => null
                            ];
                        }
                    } else {
                        // Para outros status
                        $updateData = [
                            'status' => $validated['status'],
                            'sector_id' => $patientFlow->sector_id
                        ];
                    }
                }

                $patient->update($updateData);
            }

            DB::commit();

            return redirect()
                ->route('patient-flows.show', $patientFlow)
                ->with('success', 'Fluxo do paciente atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar fluxo do paciente. Por favor, tente novamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientFlow $patientFlow)
    {
        try {
            DB::beginTransaction();

            $patientFlow->update([
                'status' => 'cancelado',
                'check_out' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('patient-flows.index')
                ->with('success', 'Fluxo do paciente cancelado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erro ao cancelar fluxo do paciente. Por favor, tente novamente.');
        }
    }

    /**
     * Iniciar atendimento do paciente.
     */
    public function startAttendance(PatientFlow $patientFlow)
    {
        try {
            DB::beginTransaction();

            // Atualiza o status do fluxo
            $patientFlow->update([
                'status' => 'em_atendimento',
                'user_id' => auth()->id(),
            ]);

            // Atualiza as informações de internação do paciente
            $patient = Patient::find($patientFlow->patient_id);
            if ($patient) {
                $patient->update([
                    'status' => 'em_atendimento',
                    'sector_id' => $patientFlow->sector_id,
                    'admission_date' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('patient-flows.show', $patientFlow)
                ->with('success', 'Atendimento iniciado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erro ao iniciar atendimento. Por favor, tente novamente.');
        }
    }

    /**
     * Finalizar atendimento do paciente.
     */
    public function finishAttendance(PatientFlow $patientFlow)
    {
        try {
            DB::beginTransaction();

            // Atualiza o status do fluxo
            $patientFlow->update([
                'status' => 'concluido',
                'check_out' => now(),
            ]);

            // Atualiza as informações de internação do paciente
            $patient = Patient::find($patientFlow->patient_id);
            if ($patient) {
                // Verifica se o paciente tem outros atendimentos ativos
                $hasActiveFlows = PatientFlow::where('patient_id', $patient->id)
                    ->where('id', '!=', $patientFlow->id)
                    ->whereNull('check_out')
                    ->exists();

                $updateData = [
                    'sector_id' => null,
                    'bed' => null,
                ];

                // Só define como alta se não houver outros atendimentos ativos
                if (!$hasActiveFlows) {
                    $updateData['status'] = 'alta';
                    $updateData['discharge_date'] = now();
                } else {
                    $updateData['status'] = 'aguardando';
                }

                $patient->update($updateData);
            }

            DB::commit();

            return redirect()
                ->route('patient-flows.show', $patientFlow)
                ->with('success', 'Atendimento finalizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erro ao finalizar atendimento. Por favor, tente novamente.');
        }
    }

    public function updateStatus(Request $request, PatientFlow $patientFlow)
    {
        $request->validate([
            'status' => 'required|in:aguardando,em_atendimento,concluido',
            'observations' => 'nullable|string',
            'conclusion' => 'nullable|string|required_if:status,concluido',
        ]);

        try {
            DB::beginTransaction();

            // Atualiza o status do fluxo
            $patientFlow->update([
                'status' => $request->status,
                'observations' => $request->observations,
                'conclusion' => $request->conclusion,
                'check_out' => $request->status === 'concluido' ? now() : null,
            ]);

            // Atualiza as informações de internação do paciente
            $patient = Patient::find($patientFlow->patient_id);
            
            if ($patient) {
                $updateData = [
                    'status' => $request->status,
                    'sector_id' => $patientFlow->sector_id,
                ];

                // Se o status for em_atendimento e não houver data de internação
                if ($request->status === 'em_atendimento' && !$patient->admission_date) {
                    $updateData['admission_date'] = now();
                }
                
                // Se o status for concluido
                if ($request->status === 'concluido') {
                    // Verifica se o paciente tem outros atendimentos ativos
                    $hasActiveFlows = PatientFlow::where('patient_id', $patient->id)
                        ->where('id', '!=', $patientFlow->id)
                        ->whereNull('check_out')
                        ->exists();

                    $updateData['sector_id'] = null;
                    $updateData['bed'] = null;

                    if (!$hasActiveFlows) {
                        $updateData['status'] = 'alta';
                        $updateData['discharge_date'] = now();
                    } else {
                        $updateData['status'] = 'aguardando';
                    }
                }

                $patient->update($updateData);
            }

            DB::commit();

            return redirect()->route('patient-flows.index')
                ->with('success', 'Status do atendimento atualizado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar status do atendimento.');
        }
    }
}
