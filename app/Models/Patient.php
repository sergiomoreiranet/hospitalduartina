<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'cpf',
        'rg',
        'birth_date',
        'gender',
        'marital_status',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'health_insurance',
        'health_insurance_number',
        'allergies',
        'chronic_diseases',
        'medications',
        'notes',
        'is_active',
        'status',
        'sector_id',
        'bed',
        'admission_date',
        'discharge_date',
        'medical_team',
        'observations'
    ];

    protected $casts = [
        'birth_date' => 'datetime',
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who deactivated this patient.
     */
    public function deactivatedBy()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function flows()
    {
        return $this->hasMany(PatientFlow::class);
    }

    public function currentFlow()
    {
        return $this->hasOne(PatientFlow::class)
            ->whereNull('check_out')
            ->latest();
    }

    public function lastFlow()
    {
        return $this->hasOne(PatientFlow::class)
            ->latest();
    }

    public function getCurrentSectorAttribute()
    {
        if ($currentFlow = $this->currentFlow) {
            return $currentFlow->sector;
        }

        return null;
    }

    public function getLastFlowAttribute()
    {
        return $this->lastFlow()->first();
    }

    public function getStatusTextAttribute()
    {
        if ($currentFlow = $this->currentFlow) {
            return match($currentFlow->status) {
                'aguardando' => 'Aguardando',
                'em_atendimento' => 'Em Atendimento',
                'concluido' => 'Concluído',
                default => 'Não internado'
            };
        }

        if ($lastFlow = $this->last_flow) {
            if ($lastFlow->status === 'concluido') {
                return 'Alta';
            }
        }

        return 'Não internado';
    }

    public function getAdmissionStatusAttribute()
    {
        if ($currentFlow = $this->currentFlow) {
            return [
                'status' => $currentFlow->status,
                'sector' => $currentFlow->sector->name,
                'admission_date' => $currentFlow->check_in,
                'bed' => $this->bed ?? 'Não informado',
                'discharge_date' => null,
                'medical_team' => $this->medical_team ?? 'Não informado',
                'observations' => $this->observations
            ];
        }

        if ($lastFlow = $this->last_flow) {
            if ($lastFlow->status === 'concluido') {
                return [
                    'status' => 'alta',
                    'sector' => 'Não internado',
                    'admission_date' => null,
                    'bed' => 'Não informado',
                    'discharge_date' => $lastFlow->check_out,
                    'medical_team' => $this->medical_team ?? 'Não informado',
                    'observations' => $this->observations
                ];
            }
        }

        return [
            'status' => 'não_internado',
            'sector' => 'Não internado',
            'admission_date' => null,
            'bed' => 'Não informado',
            'discharge_date' => null,
            'medical_team' => 'Não informado',
            'observations' => $this->observations
        ];
    }
}
