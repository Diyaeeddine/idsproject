<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Affiche la liste des utilisateurs (admins uniquement)
     */
    public function index(Request $request)
    {
        $query = User::query();

        $query->where('role', UserRole::User);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->get();

        return view('admin.profiles.profiles', compact('users'));
    }

    /**
     * Formulaire de création d'un profil utilisateur
     */
<<<<<<< Updated upstream
    public function create()
    {
        return view('admin.profiles.add-profile');
    }
    public function create_demande(){
        return view('admin.demandes.add-demande');
    }
=======
    public function createProfile()
    {
        return view('admin.profiles.add-profile');
    }
>>>>>>> Stashed changes

    /**
     * Stocke un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
<<<<<<< Updated upstream
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
=======
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
>>>>>>> Stashed changes
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        event(new Registered($user));
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
        return redirect()->route('acce.index')->with('success', 'Profil créé avec succès');

    }

    /**
     * Formulaire d'édition utilisateur
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.profiles.edit', compact('user'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('acce.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('acce.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Vue création utilisateur (redondant avec createProfile)
     */
    public function userCreate()
    {
        return view('admin.profiles.add-profile');
    }

    /**
     * Affiche les demandes assignées à l'utilisateur connecté et alertes
     */
    public function userDemandes()
    {
        $user = Auth::user();

        $mesdemandes = $user->demandes()->paginate(10);

        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '>', Carbon::now()->subMinute())
            ->get();

        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '<=', Carbon::now()->subMinute())
            ->get();

        return view('user.demandes', compact('mesdemandes', 'nouvellesDemandes', 'demandesEnRetard'));
    }

    /**
     * Endpoint pour récupérer les alertes en JSON (pour AJAX)
     */
    public function getAlerts()
    {
        $user = Auth::user();

        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '>', now()->subMinute())
            ->count();

        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '<=', now()->subMinute())
            ->count();

        return response()->json([
            'nouvelles' => $nouvellesDemandes,
            'retard' => $demandesEnRetard,
        ]);
    }
}
