<?php

namespace App\Http\Controllers;

use App\Models\BudgetTable;
use App\Models\BudgetEntry;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            'rows.*.b_title' => 'nullable|string|max:255',
        ]);

        $table = BudgetTable::create([
            'title' => $validated['title'],
            'prevision_label' => 'Prévisions Budgétaires "' . $validated['prevision'] . '"',
        ]);

        foreach ($validated['rows'] as $index => $row) {
            BudgetEntry::create([
                'budget_table_id' => $table->id,
                'imputation_comptable' => $row['imputation'] ?? null,
                'intitule' => $row['intitule'] ?? null,
                'is_header' => isset($row['is_header']) && $row['is_header'] == 1,
                'budget_previsionnel' => $row['previsionnel'] ?? null,
                'atterrissage' => $row['atterrissage'] ?? null,
                'b_title' => $row['b_title'] ?? null,
                'position' => $index,
            ]);
        }

        return redirect()->back()->with('success', 'Table budgétaire créée avec succès !');
    }

    public function index()
    {
        $tables = BudgetTable::all(); 
        $firstTable = $tables->first();

        return view('admin.budgetaire.tables-budgetaires', [
            'tables' => $tables,
            'selectedTable' => $firstTable,
        ]);
    }

    public function show($id)
    {
        $tables = BudgetTable::all(); 
        $selectedTable = BudgetTable::with('entries')->findOrFail($id);

        return view('admin.budgetaire.tables-budgetaires', [
            'tables' => $tables,
            'selectedTable' => $selectedTable,
        ]);
    }

    public function exportPdf($id)
    {
        $table = BudgetTable::with('entries')->findOrFail($id);
        $url = url("/admin/tables-budgetaires/{$id}");

        // Generate QR code
        $qrCode = base64_encode(
            QrCode::format('png')->size(100)->generate($url)
        );

        // Slugify the title for the file name
        $safeTitle = Str::slug($table->title);  // e.g. "Budget Année 2025" -> "budget-annee-2025"
        $filename = "{$safeTitle}_{$table->id}.pdf";

        // Load the view and generate PDF
        $pdf = Pdf::loadView('admin.budgetaire.export-pdf', [
            'table' => $table,
            'qrCode' => $qrCode,
            'url' => $url,
        ]);

        return $pdf->download($filename);
    }
}