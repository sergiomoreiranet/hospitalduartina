<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'password',
        'is_admin',
        'is_sector_admin',
        'sector_id',
        'is_active',
        'deactivated_by',
        'deactivated_at',
        'deactivation_reason'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_sector_admin' => 'boolean',
        'is_active' => 'boolean',
        'deactivated_at' => 'datetime'
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'cpf';
    }

    /**
     * Get the sector that the user belongs to.
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Get the user who deactivated this user.
     */
    public function deactivatedBy()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }
}
