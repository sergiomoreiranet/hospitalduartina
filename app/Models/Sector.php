<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'deactivated_by',
        'deactivated_at',
        'deactivation_reason'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'deactivated_at' => 'datetime'
    ];

    /**
     * Get all administrators of this sector
     */
    public function administrators()
    {
        return $this->hasMany(User::class)
            ->where('is_sector_admin', true)
            ->where('is_active', true);
    }

    /**
     * Get all regular users of this sector
     */
    public function regularUsers()
    {
        return $this->hasMany(User::class)
            ->where('is_sector_admin', false)
            ->where('is_active', true);
    }

    /**
     * Get all users in this sector
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function deactivatedBy()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }
}
