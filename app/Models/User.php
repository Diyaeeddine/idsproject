<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Les attributs pouvant être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Les attributs à cacher lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Les conversions de type pour les attributs.
     *
     * @var array<string, string|\Illuminate\Contracts\Database\Eloquent\CastsAttributes>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    
        'role' => UserRole::class,
    ];
    public function demandes()
    {
        return $this->belongsToMany(Demande::class, 'demande_user')
            ->withPivot('duree', 'is_filled', 'isyourturn', 'sort', 'created_at', 'updated_at');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
