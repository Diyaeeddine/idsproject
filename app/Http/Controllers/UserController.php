<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;        // <--- Important
use Carbon\Carbon;

class UserController extends Controller
{
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

    public function createProfile()
    {
        return view('admin.profiles.add-profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => UserRole::User,
        ]);

        event(new Registered($user));

        return redirect()->route('acce.index')->with('success', 'Profil créé avec succès');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.profiles.edit', compact('user'));
    }

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

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('acce.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Affiche les demandes + notifications pour l'utilisateur connecté
    public function userDemandes()
    {
        $user = Auth::user();

        $mesdemandes = $user->demandes()->paginate(10);

        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '>', now()->subMinute())
            ->get();

        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('created_at', '<=', now()->subMinute())
            ->get();

        return view('user.demandes', compact('mesdemandes', 'nouvellesDemandes', 'demandesEnRetard'));
    }

    // Endpoint AJAX (optionnel) pour récupérer nombre d’alertes
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
