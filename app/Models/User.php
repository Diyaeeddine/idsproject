<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => UserRole::class,
    ];

    /**
     * Relation entre User et Demande
     * Un utilisateur peut avoir plusieurs demandes via la table pivot
     */
    public function demandes()
    {
        return $this->belongsToMany(Demande::class, 'demande_user')
                    ->withPivot('is_filled', 'created_at', 'updated_at')
                    ->withTimestamps();
    }
}
