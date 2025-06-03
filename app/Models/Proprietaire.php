<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    protected $table = 'proprietaires'; // Nom de la table

    protected $fillable = [
        'type',                // 'physique' ou 'morale'
        'nom',                 // nom ou nom de la société
        'tel',
        'nom_societe',         // si morale
        'ice',                 // identification fiscale si morale
        'nationalite',
        'cin',
        'validite_cin',
        'caution_solidaire',
        'passeport',
    ];

    // Relations (exemple : un propriétaire peut avoir plusieurs contrats)
    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }
}
