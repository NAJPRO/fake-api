<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CommentService{
    public function create(array $data){
        return DB::transaction(function () use ($data){
            if(!isset($data['user_id'])){
                $data['user_id'] = auth()->user()->id;
            }

            $comment = Comment::create($data);

            return $comment;
        });
    }


    public function update(array $data, Comment $comment){
        return DB::transaction(function () use ($data, $comment){

            $comment->update($data);

            return $comment;
        });
    }

    public function delete(Comment $comment){
        return DB::transaction(function() use ($comment){
            return $comment->delete();
        });
    }
}
