<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type'
    ];

    /**
     * Récupérer le like d'un utilisateur
     *
     * @return MorphTo
     */
    public function likeable(): MorphTo{
        return $this->morphTo();
    }
}
