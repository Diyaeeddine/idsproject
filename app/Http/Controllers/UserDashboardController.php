<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '>', Carbon::now()->subMinute())
            ->get();

        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '<=', Carbon::now()->subMinute())
            ->get();

        return view('user.dashboard', compact('nouvellesDemandes', 'demandesEnRetard'));
    }
}
