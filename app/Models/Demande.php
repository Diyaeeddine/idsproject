<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminUser; // Ajoutez cette ligne
use App\Models\ChampsPersonnalise;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = ['titre']; 

    public function champs()
    {
        return $this->hasMany(ChampPersonnalise::class);
    }

    // Ajoutez cette relation
    public function users()
    {
        return $this->belongsToMany(User::class, 'demande_user')->withTimestamps();
    }
}
