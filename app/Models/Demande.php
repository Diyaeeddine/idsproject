<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminUser; // Ajoutez cette ligne
use App\Models\ChampsPersonnalise;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'user_id']; // Assurez-vous que 'user_id' est correct

// Dans App\Models\Demande.php
// Dans app/Models/Demande.php
public function users()
{
    return $this->belongsToMany(User::class)
                ->withPivot('date_affectation')
                ->withTimestamps();
}
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with custom fields
    public function champs()
    {
        return $this->hasMany(ChampPersonnalise::class);
    }
}
