<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected CommentService $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    // Récupérer tout les commentaire
    public function index(Request $request): JsonResponse{

        try {
            $perPage = 10;
            $search = $request->input('search');

            $query = Comment::query();

            if ($search) {
                $query->where('content', 'like', "%$search%");
            }

            $commentaire = $query->paginate($perPage);
            return response()->json([
                'status_code' => 200,
                'message' => "Liste des commentaires",
                'success' => true,
                'current_page' => $commentaire->currentPage(),
                'last_page' => $commentaire->lastPage(),
                'total' => $commentaire->total(),
                'items' => $commentaire->items(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la récupération des commentaire.',
                'exception' => $th->getMessage(),
            ], 500);
        }





    }

    // Enregistrer un commentaire
    public function store(CommentRequest $request): JsonResponse{
        try {
            $commentaire = $this->commentService->create($request->validated());
            return response()->json([
                'status_code' => 201,
                'success' => true,
                'error' => false,
                'message' => 'commentaire créé avec succès.',
                'data' => $commentaire
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la création du commentaire.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    // Affiche un commentaire spécifique
    public function show($id): JsonResponse{
        $commentaire = Comment::find($id);

        if(!$commentaire){
            return response()->json([
                'status_code' => 404,
                'success' => false,
                'error' => true,
                'message' => "commentaire introuvable",
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'success' => true,
            'error' => false,
            'data' => $commentaire,
        ]);
    }

    // Mettre à jour un commentaire
    public function update(CommentRequest $request, Comment $commentaire): JsonResponse{
        try {
            $commentaire = $this->commentService->update($request->validated(), $commentaire);
            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'Mise à jour du commentaire effectuer avec succès.',
                'data' => $commentaire
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la mise à jour du commentaire.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    // Supprimer un commentaire
    public function destroy(Comment $commentaire): JsonResponse{
        try {
            $this->commentService->delete($commentaire);

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'commentaire supprimé avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Erreur lors de la suppression du commentaire.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
