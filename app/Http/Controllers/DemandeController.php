<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
public function create(){

    $users = User::all();
    return view('admin.demandes.add-demande', compact('users'));
}
    public function adminIndex(){
        
    $demands = Demande::all();
    return view('admin.demandes', compact('demands'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name'=>['required','string','max:255'],
        'email'=>['required','string','max:255'],
        'typeDemande' => ['required', 'string', 'max:255'],
        'descDemande' => ['required', 'string', 'max:255'],
        'justDemande' => ['required', 'string', 'max:255'],
        'duree' => ['required', 'string', 'in:temporaire,permanente', 'max:100'],
        'urgence' => ['required', 'string', 'in:faible,moyenne,haute', 'max:100'],
    ]);

    $demande = Demande::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'typeDemande' => $request->typeDemande,
        'descDemande' => $request->descDemande,
        'justDemande' => $request->justDemande,
        'duree' => $request->duree,
        'urgence' => $request->urgence
    ]);

    return redirect()->back()->with('success', 'Demande créée avec succès');
}


    /**
     * Display the specified resource.
     */
    public function show(Demande $demande)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Demande $demande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demande $demande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demande $demande)
    {
        //
    }
}
