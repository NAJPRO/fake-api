<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    // Récupérer tout les users
    public function index(Request $request): JsonResponse{

        try {
            $perPage = 10;
            $search = $request->input('search');

            $query = User::query();

            if ($search) {
                $query->where('name', 'like', "%$search%");
                $query->where('role', 'like', "%$search%");

            }

            $users = $query->paginate($perPage);
            return response()->json([
                'status_code' => 200,
                'message' => "Liste des utilisateurs",
                'success' => true,
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
                'items' => $users->items(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la récupération des utilisateurs.',
                'exception' => $th->getMessage(),
            ], 500);
        }





    }

    // Enregistrer un user
    public function store(CreateUserRequest $request): JsonResponse{
        try {
            $user = $this->userService->create($request->validated());
            return response()->json([
                'status_code' => 201,
                'success' => true,
                'error' => false,
                'message' => 'Utilisateur créé avec succès.',
                'data' => $user
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la création de l\'utilisateur.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    // Affiche un user spécifique
    public function show($id): JsonResponse{
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status_code' => 404,
                'success' => false,
                'error' => true,
                'message' => "Utilisateur introuvable",
            ], 404);
        }
        return response()->json([
            'status_code' => 200,
            'success' => true,
            'error' => false,
            'data' => $user,
        ]);
    }

    // Mettre à jour un user
    public function update(UpdateUserRequest $request, User $user): JsonResponse{
        try {
            //$user->update($request->validated());
            $user = $this->userService->update($request->validated(), $user);
            return response()->json([
                'status_code' => 200,
                'success' => true,
                'error' => false,
                'message' => 'Mise à jour effectuer avec succès.',
                'data' => $user
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de la mise à jour de cet utilisateur.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    // Supprimer un user
    public function destroy(User $user): JsonResponse{
        try {
            $this->userService->delete($user);

            return response()->json([
                'status_code' => 200,
                'success' => true,
                'message' => 'utilisateur supprimé avec succès.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Erreur lors de la suppression de cet utilisateur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
