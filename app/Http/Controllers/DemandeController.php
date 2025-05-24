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
    public function userDemandes()
    {
        $user = auth()->user();
        $mesdemandes = $user->demandes()
            ->withPivot('is_filled', 'updated_at')
            ->paginate(10);
    
        return view('user.demandes', compact('mesdemandes'));
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
        ? Demande::with('users')->find($id) // Charge aussi l'utilisateur pour la demande sélectionnée
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

    $champsValides = DB::table('champ_personnalises')
        ->where('demande_id', $demandeId)
        ->whereIn('id', $selectedChampIds)
        ->count();
        
    if ($champsValides !== count($selectedChampIds)) {
        return redirect()->back()->with('error', 'Certains champs ne correspondent pas à cette demande.');
    }
    
    DB::table('champ_personnalises')
        ->whereIn('id', $selectedChampIds)
        ->update(['user_id' => $userId, 'updated_at' => now()]);
    
    DB::table('demande_user')->updateOrInsert(
        ['demande_id' => $demandeId, 'user_id' => $userId],
        ['created_at' => now(), 'updated_at' => now()]
    );
    
    DB::table('demandes')->where('id', $demandeId)->update(['updated_at' => now()]);
    
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
public function remplir(Request $request, $id){

    $demande = Demande::findOrFail($id);

    foreach ($request->input('values', []) as $champId => $value) {
        $champ = ChampPersonnalise::find($champId);
        if ($champ && $champ->demande_id == $demande->id) { 
            $champ->value = $value;
            $champ->save();
        }
    }
    return redirect()->back()->with('success','Champs mis à jour avec succès.');

}
}