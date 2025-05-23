<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    // Liste tous les budgets avec pagination
public function index()
{
    $budgets = Budget::orderBy('id', 'desc')->paginate(15);
    $users = User::all();  // si tu as besoin des users dans la vue (comme dans add-demande)
    return view('admin.budgets.index', compact('budgets', 'users'));
}

    // Montre le formulaire création
    public function create()
    {
        $budget = new Budget(); // important pour la vue (éviter erreur undefined variable)
        return view('admin.budgets.create_edit', compact('budget'));
    }

    // Sauvegarde un nouveau budget
    public function store(Request $request)
    {
        $data = $request->validate([
            'intitule' => 'required|string|max:255',
            'budget_previsionnel' => 'required|numeric|min:0',
            'atterrissage' => 'required|date',
        ]);

        Budget::create($data);

        return redirect()->route('budgets.index')->with('success', 'Budget ajouté avec succès.');
    }

    // Montre le formulaire édition
    public function edit(Budget $budget)
    {
return view('admin.budgets.create_edit', compact('budget'));
    }

    // Met à jour le budget
    public function update(Request $request, Budget $budget)
    {
        $data = $request->validate([
            'intitule' => 'required|string|max:255',
            'budget_previsionnel' => 'required|numeric|min:0',
            'atterrissage' => 'required|date',
        ]);

        $budget->update($data);

        return redirect()->route('budgets.index')->with('success', 'Budget modifié avec succès.');
    }

    // Supprime un budget
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget supprimé avec succès.');
    }
        public function show(Budget $budget)
    {
        return view('admin.budgets.show', compact('budget'));
    }
}
