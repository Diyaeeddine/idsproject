<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChampPersonnalise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\BudgetTable;
use App\Models\BudgetEntry;
use App\Models\Notification;
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
                 // Création automatique de la notification pour la demande créée, sans utilisateur affecté
        Notification::create([
            'user_id' => null,  // Pas encore affectée à un utilisateur
            'demande_id' => $demande->id,
            'titre' => $demande->titre ?? 'Nouvelle demande',
            'is_read' => false,
            'read_at' => null,
        ]);

     
         if (session()->has('selected_imputation')) {
         $imputation = session('selected_imputation');
     
         $entry = BudgetEntry::where('imputation_comptable', $imputation['imputation'])->first();
     
         if ($entry) {
             $demande->budgetEntries()->attach($entry->id);
         } else {
             logger("BudgetEntry not found for imputation: " . $imputation['imputation']);
         }
     }
     
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
     
         $fichiers = DB::table('demande_files')
             ->where('demande_id', $id)
             ->where('user_id', $user->id)
             ->orderBy('created_at', 'desc')
             ->get();
     
         return view('user.voirDemande', compact('user', 'demande', 'champs', 'fichiers'));
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
    Notification::where('demande_id', $demandeId)->update(['user_id' => $userId]);

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
public function remplir(Request $request, $id) {
    $user = Auth::user();
    $demande = Demande::findOrFail($id);
    $values = $request->input('values', []);

    if ($request->hasFile('files')) {
        $request->validate([
            'files.*' => 'file|max:10240', // Max 10MB par fichier
        ]);
    }

    // Traitement des champs personnalisés
    foreach ($values as $champId => $value) {
        $champ = ChampPersonnalise::find($champId);
        if ($champ && $champ->demande_id == $demande->id && $champ->user_id == $user->id) {
            $champ->value = $value;
            $champ->save();
        }
    }

    // Gestion de l'upload des fichiers
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $user->id . '_' . $originalName;
            
            // Stocker le fichier dans storage/app/public/demandes
            $filePath = $file->storeAs('demandes', $fileName, 'public');
            
            // Sauvegarder les informations du fichier en base de données
            // Option 1: Créer une table 'demande_files' ou 'fichiers'
            DB::table('demande_files')->insert([
                'demande_id' => $demande->id,
                'user_id' => $user->id,
                'file_name' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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

    $message = $request->hasFile('files') ? 
        'Sauvegarde avec succès. Fichiers uploadés.' : 
        'Sauvegarde avec succès.';

    return redirect()->route('user.demandes')->with('success', $message);
}
public function selectBudgetTable()
{
    $tables = BudgetTable::with('entries')->get();
    return view('admin.demandes.select-budget-table', compact('tables'));
}
public function addImputationToForm(Request $request)
{
    $request->validate([
        'imputation' => 'required|string',
        'intitule' => 'nullable|string',
    ]);

    session()->put('selected_imputation', [
    'imputation' => $request->imputation,
    'intitule' => $request->intitule,
    ]);


    return redirect()->route('demande.add-demande');
}
public function chooseBudgetTableForEntry()
{
    $tables = BudgetTable::all();
    return view('admin.demandes.choose-table-for-entry', compact('tables'));
}

public function showAddEntryForm($tableId)
{
    $budgetTable = BudgetTable::findOrFail($tableId);

    return view('admin.demandes.add-entry-to-table', [
        'budgetTable' => $budgetTable,
    ]);
}





public function saveEntryAndReturn(Request $request)
{
    $request->validate([
        'budget_table_id' => 'required|exists:budget_tables,id',
        'imputation_comptable' => 'required|string',
        'intitule' => 'required|string',
        'prevision' => 'nullable|numeric',
        'atterrissage' => 'nullable|numeric',
    ]);

    $budgetTable = BudgetTable::findOrFail($request->budget_table_id);

    $lastPosition = BudgetEntry::where('budget_table_id', $budgetTable->id)->max('position') ?? 0;

    $newEntry = BudgetEntry::create([
        'budget_table_id' => $budgetTable->id,
        'imputation_comptable' => $request->imputation_comptable,
        'intitule' => $request->intitule,
        'budget_previsionnel' => $request->prevision,
        'atterrissage' => $request->atterrissage,
        'position' => $lastPosition + 1,
    ]);

    session()->flash('selected_imputation', [
        'imputation' => $newEntry->imputation_comptable,
        'intitule' => $newEntry->intitule,
    ]);

    return redirect()->route('demande.add-demande')->with('success', 'Ligne ajoutée et sélectionnée.');
}

}