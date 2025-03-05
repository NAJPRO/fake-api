<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content'
    ];


    /**
     * Retourne les likes de ce commentaire
     *
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Retourne le post au quel est liée un commentaire
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo{
        return $this->belongsTo(Post::class);
    }

    /**
     * Retourne l'utilisateur à l'origine de ce commentaire
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
