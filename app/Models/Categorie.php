<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected $table = "categories";

    /**
     * Retourne les relations entre cette categories et les post
     *
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany{
        return $this->belongsToMany(Post::class, "post_categories");
    }
}
