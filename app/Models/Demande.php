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

    public function champs()
    {
        return $this->hasMany(ChampPersonnalise::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'demande_user');
    }


public function usersWithDurations()
{
    $users = $this->users()->orderByPivot('created_at')->get();

    $durations = [];
    $previousEnd = $this->created_at; // Date création demande

    foreach ($users as $user) {
        $completedAt = $user->pivot->updated_at ?? now();

        // Calcul de la durée en secondes entre previousEnd et completedAt
        $durationSeconds = $previousEnd->diffInSeconds($completedAt);

        // Formater la durée selon la taille
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
        ];

        $previousEnd = $completedAt;
    }

    return $durations;
}
}
