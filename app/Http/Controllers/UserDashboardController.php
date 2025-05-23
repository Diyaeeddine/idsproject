<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    /**
     * Affiche le dashboard de l'utilisateur avec les notifications
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer les nouvelles demandes
        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '>', Carbon::now()->subMinute())
            ->get();

        // Récupérer les demandes en retard
        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '<=', Carbon::now()->subMinute())
            ->get();

        return view('user.dashboard', compact('nouvellesDemandes', 'demandesEnRetard'));
    }
}
