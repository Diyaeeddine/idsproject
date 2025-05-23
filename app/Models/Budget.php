<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['intitule', 'budget_previsionnel', 'atterrissage'];

    public function demandesBudgets()
    {
        return $this->hasMany(DemandeBudget::class);
    }
}
