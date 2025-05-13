<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DemandeUser extends Pivot
{
    protected $table = 'demande_user';

    protected $fillable = [
        'demande_id',
        'user_id',
        'date_affectation',

    ];

    protected $casts = [
        'date_affectation' => 'datetime',
    ];
}
