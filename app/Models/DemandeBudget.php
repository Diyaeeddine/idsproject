<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeBudget extends Model
{
    protected $table = 'demande_budget'; // si le nom diffère de la convention Laravel

    protected $fillable = [
        'budget_id',
        'titre',
        'user_id',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
