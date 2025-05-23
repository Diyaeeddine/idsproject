<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChampPersonnalise;
use App\Models\User;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = ['titre'];

    /**
     * Relation entre Demande et ChampPersonnalise
     * Une demande peut avoir plusieurs champs personnalisés
     */
    public function champs()
    {
        return $this->hasMany(ChampPersonnalise::class);
    }

    /**
     * Relation entre Demande et User
     * Une demande peut être associée à plusieurs utilisateurs via la table pivot
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'demande_user');
    }

    /**
     * Fonction qui calcule la durée entre l'attribution de la demande et la complétion
     */
    public function usersWithDurations()
    {
        $users = $this->users()->orderByPivot('created_at')->get();

        $durations = [];
        $previousEnd = $this->created_at;

        foreach ($users as $user) {
            $completedAt = $user->pivot->updated_at ?? now();

            $durationSeconds = $previousEnd->diffInSeconds($completedAt);

            if ($durationSeconds < 60) {
                $duration = $durationSeconds . ' secondes';
            } elseif ($durationSeconds < 3600) {
                $minutes = floor($durationSeconds / 60);
                $duration = $minutes . ' minutes';
            } else {
                $hours = floor($durationSeconds / 3600);
                $duration = $hours . ' heures';
            }

            $durations[] = [
                'user' => $user,
                'duration' => $duration,
                'assigned_at' => $user->pivot->created_at,
                'completed_at' => $completedAt,
                'is_filled' => $user->pivot->is_filled,
            ];

            $previousEnd = $completedAt;
        }

        return $durations;
    }
}
