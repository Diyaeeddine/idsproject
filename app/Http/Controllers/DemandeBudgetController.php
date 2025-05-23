<?php

namespace App\Http\Controllers;

use App\Models\DemandeBudget;
use App\Models\Budget;
use Illuminate\Http\Request;

class DemandeBudgetController extends Controller
{
    public function createForBudget($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);
        return view('demande_budgets.create', compact('budget'));
    }

    public function storeForBudget(Request $request, $budgetId)
    {
        $budget = Budget::findOrFail($budgetId);

        $request->validate([
            'titre' => 'required|string|max:255',
        ]);

        DemandeBudget::create([
            'budget_id' => $budget->id,
            'titre' => $request->input('titre'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('budgets.show', $budget->id)->with('success', 'Demande budgétaire créée avec succès.');
    }
}
