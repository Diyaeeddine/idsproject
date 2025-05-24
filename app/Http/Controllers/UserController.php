<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;  // Importer la classe Hash
use Illuminate\Validation\Rules\Password;  // Importer la classe Password
use Illuminate\Auth\Events\Registered;  // Importer la classe Registered
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = User::query();

    $query->where('role', UserRole::User);

    if ($request->has('search') && $request->search !== null) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $users = $query->latest()->get();

    return view('admin.profiles.profiles', compact('users'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }
    public function createProfile()
    {
        return view('admin.profiles.add-profile');
    }
    
    /**
     * Store a newly created resource in storage.
     */
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
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user=User::find($id);
        return view('admin.profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('acce.index')->with('success', 'Utilisateur supprimé avec succès.');

    }
    public function userCreate(){
        return view('admin.profiles.add-profile');

    }
    public function userDemandes()
    {
        $user = Auth::user();
        $mesdemandes = $user->demandes()->paginate(10);
        
        // Calculer le temps écoulé pour chaque demande
        foreach ($mesdemandes as $demande) {
            $createdAt = $demande->created_at;
            $now = now();
            
            // Calcul de la différence en minutes
            $diffInMinutes = $createdAt->diffInMinutes($now);
            
            // Formatage du temps écoulé
            if ($diffInMinutes < 60) {
                $demande->temps_ecoule = $diffInMinutes . ' min';
                $demande->temps_ecoule_minutes = $diffInMinutes;
            } elseif ($diffInMinutes < 1440) { // moins de 24h
                $hours = floor($diffInMinutes / 60);
                $minutes = $diffInMinutes % 60;
                $demande->temps_ecoule = $hours . 'h ' . $minutes . 'min';
                $demande->temps_ecoule_minutes = $diffInMinutes;
            } else { // plus de 24h
                $days = floor($diffInMinutes / 1440);
                $hours = floor(($diffInMinutes % 1440) / 60);
                $demande->temps_ecoule = $days . 'j ' . $hours . 'h';
                $demande->temps_ecoule_minutes = $diffInMinutes;
            }
        }
        
        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('updated_at', '>', now()->subMinute())
            ->get();
             
        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('updated_at', '<=', now()->subMinute())
            ->get();
             
        return view('user.demandes', compact('mesdemandes', 'nouvellesDemandes', 'demandesEnRetard'));
    }

    public function getAlerts()
    {
        $user = Auth::user();

        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('updated_at', '>', now()->subMinute())
            ->count();

        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('updated_at', '<=', now()->subMinute())
            ->count();

        return response()->json([
            'nouvelles' => $nouvellesDemandes,
            'retard' => $demandesEnRetard,
        ]);
    }
    public function userDashboard()
{
    $user = Auth::user();

    $nouvellesDemandes = $user->demandes()
        ->wherePivot('is_filled', false)
        ->wherePivot('updated_at', '>', now()->subMinute())
        ->get();

    $demandesEnRetard = $user->demandes()
        ->wherePivot('is_filled', false)
        ->wherePivot('updated_at', '<=', now()->subMinute())
        ->get();

    return view('user.dashboard', [
        'nouvellesDemandes' => $nouvellesDemandes,
        'demandesEnRetard' => $demandesEnRetard,
    ]);
}
public function remplirDemande(){
    return '';
}
}
