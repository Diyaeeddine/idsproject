<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Demande;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password', // Ajout important pour l'authentification
        'role',
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
        'role' => UserRole::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation many-to-many avec les demandes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // Many-to-Many relationship with Demandes through demande_user pivot table
// Dans app/Models/User.php
public function demandes()
{
    return $this->belongsToMany(Demande::class)
                ->withPivot('date_affectation')
                ->withTimestamps();
}

    /**
     * Vérifie si l'utilisateur a un rôle admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Vérifie si l'utilisateur a un rôle user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === UserRole::User;
    }

    /**
     * Scope pour filtrer les utilisateurs par rôle.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Set the user's password (auto-hashed).
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
