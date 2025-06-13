<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Validation\Rules\Password;  
use Illuminate\Auth\Events\Registered;  
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    
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


   
    public function create()
    {
       //
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
    

    public function show(string $id)
    {
        //
    }

    

    public function edit(string $id)
    {
        $user=User::find($id);
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
    public function userCreate(){
        return view('admin.profiles.add-profile');

    }
    public function userDemandes()
{
    $user = Auth::user();

    $mesdemandes = $user->demandes()
    ->where(function ($query) {
        $query->where(function ($q) {
            $q->where('demande_user.isyourturn', true)
              ->orWhere('demande_user.is_filled', true);
        });
    })
    ->paginate(10);

    foreach ($mesdemandes as $demande) {
        $updated_at = $demande->updated_at;
        $now = now();

        $diffInMinutes = round($updated_at->diffInMinutes($now));

        if ($diffInMinutes < 60) {
            $demande->temps_ecoule = $diffInMinutes . ' min';
            $demande->temps_ecoule_minutes = $diffInMinutes;
        } elseif ($diffInMinutes < 1440) {
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;
            $demande->temps_ecoule = $hours . 'h ' . $minutes . 'min';
            $demande->temps_ecoule_minutes = $diffInMinutes;
        } else {
            $days = floor($diffInMinutes / 1440);
            $hours = floor(($diffInMinutes % 1440) / 60);
            $demande->temps_ecoule = $days . 'j ' . $hours . 'h';
            $demande->temps_ecoule_minutes = $diffInMinutes;
        }
    }

    $nouvellesDemandes = $user->demandes()
        ->wherePivot('is_filled', false)
        ->wherePivot('updated_at', '>', now()->subMinutes(60))
        ->get();

    $demandesEnRetard = $user->demandes()
        ->wherePivot('is_filled', false)
        ->wherePivot('updated_at', '<=', now()->subMinutes(60))
        ->get();

    return view('user.demandes', compact('mesdemandes', 'nouvellesDemandes', 'demandesEnRetard'));
}


    public function getAlerts()
    {
        $user = Auth::user();

        $nouvellesDemandes = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('updated_at', '>', now()->subMinute(60))
            ->count();

        $demandesEnRetard = $user->demandes()
            ->wherePivot('is_filled', false)
            ->wherePivot('updated_at', '<=', now()->subMinute(60))
            ->count();

        return response()->json([
            'nouvelles' => $nouvellesDemandes,
            'retard' => $demandesEnRetard,
        ]);
    }



    
    public function userDashboard(Request $request)
    {
        $user = $request->user();

        $contrats = $user->contrats()->latest()->get();
        $factures = $user->factures()->latest()->get();

        $contractCount = $contrats->count();
        $invoiceCount = $factures->count();
        $unpaidInvoicesCount = $factures->where('statut', 'non payée')->count();
        $totalOwed = $factures->where('statut', 'non payée')->sum('total_ttc');

        $recentContrats = $contrats->take(5);

        $data = [
            'contractCount' => $contractCount,
            'invoiceCount' => $invoiceCount,
            'unpaidInvoicesCount' => $unpaidInvoicesCount,
            'totalOwed' => $totalOwed,
            'recentContrats' => $recentContrats,
        ];

        return view('user.dashboard', $data);
    }

    
public function notificationUser()
{
    $user = Auth::user();

    $demandes = $user->demandes()->orderByDesc('pivot_updated_at')->take(5)->get()->map(function ($demande) {
        $demande->temps = Carbon::parse($demande->pivot->updated_at)->diffForHumans();
        return $demande;
    });

    return view('layouts.navigation', compact('demandes'));
}






}

    
   