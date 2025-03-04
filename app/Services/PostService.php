<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PostService{
    public function createPost(array $data){
        return DB::transaction(function () use ($data){
            // Générer un slug si il n'as pas été fourni
            if(!isset($data['slug'])){
                $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
            }
            if(!isset($data['user_id'])){
                $data['user_id'] = auth()->user()->id;
                //$data['user_id'] = 1;

            }
            if($data['status'] === 'published'){
                $data['published_at'] = now();
            }
            $post = Post::create($data);

            // Associer les catégories (s'assurer que `categorie_id` est un tableau)
            if (!empty($data['categorie_id']) && is_array($data['categorie_id'])) {
                $post->categories()->attach($data['categorie_id']);
            }

            // Associer les tags au post
            if (!empty($data['tags_id']) && is_array($data['tags_id'])) {
                $post->tags()->attach($data['tags_id']);
            }

            return $post;
        });
    }


    public function updatePost(array $data, Post $post){
        return DB::transaction(function () use ($data, $post){
            // Générer un slug si il n'as pas été fourni
            if(isset($data['title']) && !isset($data['slug'])){
                $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
            }
            if($data['status'] === 'published' && $post->status === 'draft'){
                $data['published_at'] = now();
            }
            $post->update($data);

            if (!empty($data['categorie_id']) && is_array($data['categorie_id'])) {
                $post->categories()->sync($data['categorie_id']);
            }

            if (!empty($data['tags_id']) && is_array($data['tags_id'])) {
                $post->tags()->sync($data['tags_id']);
            }

            return $post;
        });
    }

    public function deletePost(Post $post){
        return DB::transaction(function() use ($post){
            return $post->delete();
        });
    }
}
