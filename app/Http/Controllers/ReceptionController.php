<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientFlow;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionController extends Controller
{
    public function index()
    {
        $receptionSector = Sector::where('name', 'Recepção')->first();
        
        $patientFlows = PatientFlow::with(['patient', 'sector'])
            ->where('sector_id', $receptionSector->id)
            ->whereNull('check_out')
            ->orderBy('is_priority', 'desc')
            ->orderBy('queue_position', 'asc')
            ->paginate(10);

        return view('reception.index', compact('patientFlows'));
    }

    public function triage(PatientFlow $patientFlow)
    {
        if ($patientFlow->sector->name !== 'Recepção') {
            return redirect()->route('reception.index')
                ->with('error', 'Este paciente não está na recepção.');
        }

        return view('reception.triage', compact('patientFlow'));
    }

    public function updateTriage(Request $request, PatientFlow $patientFlow)
    {
        $validated = $request->validate([
            'triage_level' => 'required|in:emergencia,urgencia,prioridade,normal',
            'vital_signs' => 'required|array',
            'vital_signs.pressure' => 'required|string',
            'vital_signs.temperature' => 'required|numeric',
            'vital_signs.heart_rate' => 'required|integer',
            'vital_signs.respiratory_rate' => 'required|integer',
            'vital_signs.oxygen_saturation' => 'required|integer',
            'main_symptoms' => 'required|string',
            'risk_factors' => 'nullable|string',
            'manchester_color' => 'required|in:vermelho,laranja,amarelo,verde,azul',
            'next_sector_id' => 'required|exists:sectors,id'
        ]);

        try {
            DB::beginTransaction();

            // Atualiza a triagem
            $patientFlow->update($validated);

            // Cria o novo fluxo para o próximo setor
            $lastPosition = PatientFlow::where('sector_id', $validated['next_sector_id'])
                ->whereNull('check_out')
                ->max('queue_position') ?? 0;

            PatientFlow::create([
                'patient_id' => $patientFlow->patient_id,
                'sector_id' => $validated['next_sector_id'],
                'user_id' => auth()->id(),
                'status' => 'aguardando',
                'check_in' => now(),
                'queue_position' => $lastPosition + 1,
                'is_priority' => in_array($validated['triage_level'], ['emergencia', 'urgencia']),
            ]);

            // Finaliza o fluxo na recepção
            $patientFlow->update([
                'status' => 'concluido',
                'check_out' => now()
            ]);

            DB::commit();

            return redirect()
                ->route('reception.index')
                ->with('success', 'Triagem realizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao realizar triagem. Por favor, tente novamente.');
        }
    }
} 