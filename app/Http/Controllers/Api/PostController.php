<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    // Récupérer tout les posts
    public function index(Request $request): JsonResponse{

        try {
            $perPage = 10;
            $search = $request->input('search');
            //$posts = Post::with('categories')->get();
            //dd($posts);
            $query = Post::query();

            if ($search) {
                $query->where('title', 'like', "%$search%");
            }

            $posts = $query->paginate($perPage);
            return response()->json([
                'status_code' => 200,
                'message' => "Liste des posts",
                'success' => true,
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
                'items' => $posts->items(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la récupération des posts.',
                'exception' => $th->getMessage(),
            ], 500);
        }





    }

    // Enregistrer un post
    public function store(CreatePostRequest $request): JsonResponse{
        try {
            //$post = Post::create($request->validated());
            $post = $this->postService->createPost($request->validated());
            return response()->json([
                'status_code' => 201,
                'success' => true,
                'error' => false,
                'message' => 'Post créé avec succès.',
                'data' => $post
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la création du post.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    // Affiche un post spécifique
    public function show($id): JsonResponse{
        $post = Post::find($id);

        if(!$post){
            return response()->json([
                'status_code' => 404,
                'success' => false,
                'error' => true,
                'message' => "Post introuvable",
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'success' => true,
            'error' => false,
            'data' => $post,
        ]);
    }

    // Mettre à jour un post
    public function update(CreatePostRequest $request, Post $post): JsonResponse{
        try {
            //$post->update($request->validated());
            $post = $this->postService->updatePost($request->validated(), $post);
            return response()->json([
                'status_code' => 200,
                'success' => true,
                'error' => false,
                'message' => 'Mise à jour du post effectuer avec succès.',
                'data' => $post
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la mise à jour du post.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    // Supprimer un post
    public function destroy(Post $post): JsonResponse{
        try {
            $this->postService->deletePost($post);

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'Post supprimé avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Erreur lors de la suppression du post.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
