<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tags extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected $table = "tags";

    /**
     * Retourne les posts liée à ce Tag
     *
     * @return BelongsToMany
     */
    public function posts(): BelongsToMany{
        return $this->belongsToMany(Post::class, "post_tags");
    }
}
