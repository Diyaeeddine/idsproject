<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Http\Models\ChampsPersonnalise;
class Demande extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'user_id'];


    public function champs()
    {
        return $this->hasMany(ChampPersonnalise::class);
    }
}
