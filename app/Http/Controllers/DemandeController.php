<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChampPersonnalise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        $demands = Demande::with('users')->latest()->get();
        return view('admin.demandes.show-demande', compact('demands'));
    }
    

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $users = User::all();
        return view('admin.demandes.add-demande', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $request->validate([
        'titre' => 'required|string|max:255',
    ]);

    $demande = Demande::create([
        'titre' => $request->input('titre'),
        'user_id' => null,
    ]);

    $fields = $request->input('fields', []);

    foreach ($fields as $field) {
        ChampPersonnalise::create([
            'key' => $field['key'],
            'value' => null,
            'demande_id' => $demande->id,
        ]);
    }

    return redirect()->back()->with('success', 'Demande créée avec succès');
}

public function show($id)
{
    $user = Auth::user(); 

    $demande = Demande::findOrFail($id);

    $champs = ChampPersonnalise::where('demande_id', $id)
        ->where('user_id', $user->id)
        ->get();

    return view('user.voirDemande', compact('user', 'demande', 'champs'));
}


    public function edit(Demande $demande)
    {
        //
    }


    public function update(Request $request, Demande $demande)
    {
        //
    }

    public function destroy(Demande $demande)
    {
        //
    }

    public function affecterPage($id = null)
    {
        $demandes = Demande::with('champs')->latest()->get(); 
        $users = User::where('role','user')->get();

        $selectedDemande = $id ? Demande::with('champs')->findOrFail($id) : null;

        return view('admin.demandes.affecter-demande', compact('demandes', 'users', 'selectedDemande'));
    }


public function demandePage($id = null)
{
    $demandes = Demande::with('users')->latest()->get();

    if ($demandes->isEmpty()) {
        abort(404, 'Aucune demande en base');
    }

    $selectedDemande = $id
        ? Demande::with('users')->find($id)
        : $demandes->first();

    if (!$selectedDemande) {
        return redirect()->route('demande', $demandes->first()->id);
    }

    return view('admin.demandes.show-demande', [
        'demandes' => $demandes,
        'selectedDemande' => $selectedDemande,
    ]);
}


public function affecterChamps(Request $request, $demandeId) 
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'champs_selected' => 'required|array|min:1',
        'champs_selected.*' => 'exists:champ_personnalises,id'
    ]);

    $userId = $request->input('user_id');
    $selectedChampIds = $request->input('champs_selected', []);
    $demande = Demande::findOrFail($demandeId);

    $existing = DB::table('demande_user')
        ->where('demande_id', $demandeId)
        ->where('user_id', $userId)
        ->first();

    if ($existing) {
        return redirect()->back()->with('error', 'Cet utilisateur est déjà affecté à cette demande.');
    }

    $lastSort = DB::table('demande_user')
    ->where('demande_id', $demandeId)
    ->max('sort');

$nextSort = $lastSort ? $lastSort + 1 : 1;

$demande->users()->attach($userId, [
    'sort' => $nextSort,
    'isyourturn' => ($nextSort === 1), // Fix casing to match DB field
    'is_filled' => false,
    'created_at' => now(),
    'updated_at' => now(),
]);


    $champsValidesCount = DB::table('champ_personnalises')
        ->where('demande_id', $demandeId)
        ->whereIn('id', $selectedChampIds)
        ->count();

    if ($champsValidesCount !== count($selectedChampIds)) {
        return redirect()->back()->with('error', 'Certains champs ne correspondent pas à cette demande.');
    }

    DB::table('champ_personnalises')
        ->whereIn('id', $selectedChampIds)
        ->update([
            'user_id' => $userId,
            'updated_at' => now()
        ]);

    $demande->updated_at = now();
    $demande->save();

    $nombreChamps = count($selectedChampIds);
    $userName = DB::table('users')->find($userId)->name;
    $message = $nombreChamps === 1 ? "1 champ affecté à {$userName}" : "{$nombreChamps} champs affectés à {$userName}";

    return redirect()->route('demandes.affecter', $demandeId)->with('success', $message);
}


public function showRemplir($id){
    $user = Auth::user(); 

    $demande = Demande::findOrFail($id);

    $champs = ChampPersonnalise::where('demande_id', $id)
        ->where('user_id', $user->id)
        ->get();

    return view('user.remplirDemande', compact('user', 'demande', 'champs'));
}
public function remplir(Request $request, $id)
{
    $user = Auth::user();
    $demande = Demande::findOrFail($id);
    $values = $request->input('values', []);

    foreach ($values as $champId => $value) {
        $champ = ChampPersonnalise::find($champId);
        if ($champ && $champ->demande_id == $demande->id && $champ->user_id == $user->id) {
            $champ->value = $value;
            $champ->save();
        }
    }

    $champs = ChampPersonnalise::where('demande_id', $id)
        ->where('user_id', $user->id)
        ->get();

    $allFilled = $champs->every(fn($champ) => !is_null($champ->value) && trim($champ->value) !== '');

    if ($allFilled) {
        $temps_ecoule = $request->query('temps_ecoule') ?? $request->input('temps_ecoule');

        if ($temps_ecoule) {
            $user->demandes()->updateExistingPivot($demande->id, ['duree' => $temps_ecoule]);
        }

        $user->demandes()->updateExistingPivot($demande->id, [
            'is_filled' => true,
            'isyourturn' => false
        ]);

        $currentSort = DB::table('demande_user')
            ->where('demande_id', $demande->id)
            ->where('user_id', $user->id)
            ->value('sort');

        $nextUser = DB::table('demande_user')
            ->where('demande_id', $demande->id)
            ->where('sort', $currentSort + 1)
            ->first();

        if ($nextUser) {
            DB::table('demande_user')
                ->where('demande_id', $demande->id)
                ->where('user_id', $nextUser->user_id)
                ->update(['isyourturn' => true]);
        }
    } else {
        $user->demandes()->updateExistingPivot($demande->id, ['is_filled' => false]);
    }

    return redirect()->route('user.demandes')->with('success', 'Sauvegarde avec succès.');
}
}