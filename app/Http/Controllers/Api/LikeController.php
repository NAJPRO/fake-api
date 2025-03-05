<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    public function toggleLike($type, $id): JsonResponse{
        try {
            $user = auth()->user();
            /**
             * @model_liked : Post | Comment
             */

            $model_liked = $type === 'posts' ? Post::class : Comment::class;
            $model = $model_liked::findOrFail($id);
            $like = $model->likes()->where('user_id', $user->id)->first();
            if($like){
                // Si le model est déjà liker
                $like->delete();
                return response()->json([
                    'message' => 'Like retiré',
                    'success' => true,
                    'error' => false,
                    'code_status' => 200,
                    'liked' => false
                ], 200);
            }else {
                // Ajouter un like
                $model->likes()->create(['user_id' => $user->id]);
                return response()->json([
                    'message' => 'Post liké',
                    'success' => true,
                    'code_status' => 200,
                    'liked' => true
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Une erreur est survenu',
                'error' => true,
                'code_status' => 500
            ], 500);
        }



    }

}
