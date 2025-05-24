<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetEntry extends Model
{
    protected $fillable = [
    'budget_table_id',
    'imputation_comptable',
    'intitule',
    'is_header',
    'budget_previsionnel',
    'atterrissage',
];
    public function budgetTable()
    {
        return $this->belongsTo(BudgetTable::class);
    }
}
