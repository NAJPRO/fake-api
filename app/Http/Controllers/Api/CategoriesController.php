<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriesRequest;
use App\Models\Categorie;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    protected CategoryService $categorieService;
    public function __construct(CategoryService $category_service)
    {
        $this->categorieService = $category_service;
    }
    public function index(Request $request): JsonResponse{

        try {
            $perPage = 10;
            $search = $request->input('search');

            $query = Categorie::query();

            if ($search) {
                $query->where('name', 'like', "%$search%");
            }

            $categories = $query->paginate($perPage);
            return response()->json([
                'status_code' => 200,
                'message' => "Liste des categories",
                'success' => true,
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'total' => $categories->total(),
                'items' => $categories->items(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la récupération des categories.',
                'exception' => $th->getMessage(),
            ], 500);
        }





    }

    /**
     * Enregistrer une catégorie
     *
     * @param CategoriesRequest $request
     * @return JsonResponse
     */
    public function store(CategoriesRequest $request): JsonResponse{
        try {
            $categorie = $this->categorieService->create($request->validated());
            return response()->json([
                'status_code' => 201,
                'success' => true,
                'error' => false,
                'message' => 'Catégorie créé avec succès.',
                'data' => $categorie
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la création de la catégorie.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche une catégorie spécifique
     *
     * @param [Int] $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse{
        $categorie = Categorie::find($id);

        if(!$categorie){
            return response()->json([
                'status_code' => 404,
                'success' => false,
                'error' => true,
                'message' => "Catégorie introuvable",
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'success' => true,
            'error' => false,
            'data' => $categorie,
        ]);
    }

    /**
     * Modifier une catégorie
     *
     * @param CategoriesRequest $request
     * @param Categorie $category
     * @return JsonResponse
     */
    public function update(CategoriesRequest $request, Categorie $category): JsonResponse{
        try {
            $category = $this->categorieService->update($request->validated(), $category);
            return response()->json([
                'status_code' => 200,
                'success' => true,
                'error' => false,
                'message' => 'Mise à jour de la categorie effectuer avec succès.',
                'data' => $category
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la mise à jour de la categorie.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une catégorie
     *
     * @param Categorie $category
     * @return JsonResponse
     */
    public function destroy(Categorie $category): JsonResponse{
        try {
            $this->categorieService->delete($category);

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'categorie supprimé avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Erreur lors de la suppression de la categorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
