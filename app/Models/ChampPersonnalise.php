<?php

namespace App\Models;
// use Http\Models\Demande; // Cette ligne est incorrecte
use App\Models\Demande; // Corrigez ceci
use Illuminate\Database\Eloquent\Model;

class ChampPersonnalise extends Model
{
    protected $fillable = ['key', 'value', 'demande_id'];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
