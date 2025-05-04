<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientFlow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'sector_id',
        'user_id',
        'status',
        'check_in',
        'check_out',
        'next_sector_id',
        'observations',
        'conclusion',
        'is_priority',
        'queue_position',
        'triage_level',
        'vital_signs',
        'main_symptoms',
        'risk_factors',
        'manchester_color'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'is_priority' => 'boolean',
        'vital_signs' => 'array'
    ];

    // Relacionamentos
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function nextSector()
    {
        return $this->belongsTo(Sector::class, 'next_sector_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Acessores
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'aguardando' => 'Aguardando',
            'em_atendimento' => 'Em Atendimento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
            default => 'Desconhecido'
        };
    }

    public function getDurationAttribute()
    {
        if (!$this->check_in) {
            return 0;
        }

        $end = $this->check_out ?? now();
        return $this->check_in->diffInMinutes($end);
    }

    public function getTriageLevelTextAttribute()
    {
        return match($this->triage_level) {
            'emergencia' => 'Emergência',
            'urgencia' => 'Urgência',
            'prioridade' => 'Prioridade',
            'normal' => 'Normal',
            default => 'Não classificado'
        };
    }

    public function getManchesterColorTextAttribute()
    {
        return match($this->manchester_color) {
            'vermelho' => 'Vermelho',
            'laranja' => 'Laranja',
            'amarelo' => 'Amarelo',
            'verde' => 'Verde',
            'azul' => 'Azul',
            default => 'Não classificado'
        };
    }

    // Escopo para buscar fluxos ativos
    public function scopeActive($query)
    {
        return $query->whereNull('check_out');
    }

    // Escopo para buscar por setor
    public function scopeInSector($query, $sectorId)
    {
        return $query->where('sector_id', $sectorId);
    }

    // Escopo para buscar por status
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('is_priority', 'desc')
            ->orderBy('queue_position', 'asc')
            ->orderBy('check_in', 'asc');
    }
}
