<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChampPersonnalise;
use App\Models\User;
use Carbon\Carbon;
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
        return $this->belongsToMany(User::class, 'demande_user')
            ->withPivot('duree', 'created_at', 'updated_at'); // Champs pivot utilisés
    }


    public function usersWithDurations()
{
    $users = $this->users()->orderByPivot('created_at')->get();

    $durations = [];

    foreach ($users as $user) {
        $assignedAt = Carbon::parse($user->pivot->created_at)
            ->timezone(config('app.timezone'));
        $completedAt = Carbon::parse($user->pivot->updated_at)
            ->timezone(config('app.timezone'));
        $duration = $user->pivot->duree;

        $durations[] = [
            'user' => $user,
            'duration' => $duration,
            'assigned_at' => $assignedAt,
            'completed_at' => $completedAt,
        ];
    }

    return $durations;
}
    public function budgetEntries()
    {
        return $this->belongsToMany(BudgetEntry::class, 'budget_entry_demande');
    }
}
