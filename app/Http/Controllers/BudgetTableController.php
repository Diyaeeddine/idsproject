<?php

namespace App\Http\Controllers;

use App\Models\BudgetTable;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;

class BudgetTableController extends Controller
{
    public function create()
    {
        return view('admin.budgetaire.create-budgetaire');
    }

    public function store(Request $request)
{
        $validated = $request->validate([
        'title' => 'required|string|max:255',
        'prevision' => 'required|integer',
        'rows' => 'required|array',
        'rows.*.imputation' => 'nullable|string|max:255',
        'rows.*.intitule' => 'nullable|string|max:255',
        'rows.*.is_header' => 'nullable|boolean',
        'rows.*.previsionnel' => 'nullable|numeric',
        'rows.*.atterrissage' => 'nullable|numeric',
        
    ]);


    $table = BudgetTable::create([
        'title' => $validated['title'],
        'prevision_label' => 'Prévisions Budgétaires "' . $validated['prevision'] . '"',
    ]);

    foreach ($validated['rows'] as $row) {
        BudgetEntry::create([
            'budget_table_id' => $table->id,
            'imputation_comptable' => $row['imputation'] ?? null,
            'intitule' => $row['intitule'] ?? null,
            'is_header' => isset($row['is_header']) && $row['is_header'] == 1,
            'budget_previsionnel' => $row['previsionnel'] ?? null,
            'atterrissage' => $row['atterrissage'] ?? null,
            
        ]);
    }

    return redirect()->back()->with('success', 'Table budgétaire créée avec succès !');
}

public function index()
{
    $tables = BudgetTable::all(); // get all tables
    $firstTable = $tables->first();

    return view('admin.budgetaire.tables-budgetaires', [
        'tables' => $tables,
        'selectedTable' => $firstTable,
    ]);
}

public function show($id)
{
    $tables = BudgetTable::all(); // sidebar
    $selectedTable = BudgetTable::with('entries')->findOrFail($id);

    return view('admin.budgetaire.tables-budgetaires', [
        'tables' => $tables,
        'selectedTable' => $selectedTable,
    ]);
}

}