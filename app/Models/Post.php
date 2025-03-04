<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'status',
        'content',
        'published_at',
    ];

    protected $table = "posts";
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }


    public function user(): BelongsTo{
        /**
         * Retourne l'utilisateur qui a publier ce post
         * @return BelongsTo<User>
         */
        return $this->belongsTo(User::class);
    }

    /**
     * Retourne les catégories de ce post
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany{
        return $this->belongsToMany(Categorie::class, "post_categories");
    }


    /**
     * Retourne le tags de ce post
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany{
        return $this->belongsToMany(Tags::class, 'post_tags');
    }

     /**
     * Retourne les likes de cet utilisateur
     *
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}

