<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tags;
use App\Services\TagService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected TagService $tagService;
    public function __construct(TagService $tag_service)
    {
        $this->tagService = $tag_service;
    }
    public function index(Request $request): JsonResponse{

        try {
            $perPage = 10;
            $search = $request->input('search');

            $query = Tags::query();

            if ($search) {
                $query->where('name', 'like', "%$search%");
            }

            $tags = $query->paginate($perPage);
            return response()->json([
                'status_code' => 200,
                'message' => "Liste des tags",
                'success' => true,
                'current_page' => $tags->currentPage(),
                'last_page' => $tags->lastPage(),
                'total' => $tags->total(),
                'items' => $tags->items(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la récupération des tags.',
                'exception' => $th->getMessage(),
            ], 500);
        }





    }

    // Enregistrer un tag
    public function store(TagRequest $request): JsonResponse{
        try {
            $tag = $this->tagService->create($request->validated());
            return response()->json([
                'status_code' => 201,
                'success' => true,
                'error' => false,
                'message' => 'Tag créé avec succès.',
                'data' => $tag
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la création du tag.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche un tag spécifique
     *
     * @param [type] $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse{
        $tag = Tags::find($id);

        if(!$tag){
            return response()->json([
                'status_code' => 404,
                'success' => false,
                'error' => true,
                'message' => "Tag introuvable",
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'success' => true,
            'error' => false,
            'data' => $tag,
        ]);
    }

    /**
     * Mettre à jour un tag
     *
     * @param TagRequest $request
     * @param Tags $tag
     * @return JsonResponse
     */
    public function update(TagRequest $request, Tags $tag): JsonResponse{
        try {
            $tag = $this->tagService->update($request->validated(), $tag);
            return response()->json([
                'status_code' => 200,
                'success' => true,
                'error' => false,
                'message' => 'Mise à jour du tag effectuer avec succès.',
                'data' => $tag
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la mise à jour du tag.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un tag
     *
     * @param Tags $tag
     * @return JsonResponse
     */
    public function destroy(Tags $tag): JsonResponse{
        try {
            $this->tagService->delete($tag);

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'tag supprimé avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Erreur lors de la suppression du tag.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
