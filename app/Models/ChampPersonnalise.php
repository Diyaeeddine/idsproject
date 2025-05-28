<?php

namespace App\Models;
use Http\Models\Demande;
use Illuminate\Database\Eloquent\Model;

class ChampPersonnalise extends Model
{
    protected $fillable = ['key', 'value', 'demande_id'];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
