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

    public function champs()
    {
        return $this->hasMany(ChampPersonnalise::class);
    }

    // Ajoutez cette relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Spécifiez la clé étrangère
    }
}
