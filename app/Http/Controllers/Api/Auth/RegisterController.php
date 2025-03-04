<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Register user API
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = DB::transaction(function () use ($request) {
                return User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role ? $request->role : 'client',
                ]);
            });

            $token = $user->createToken('NAJ_PRO_FREELANCE_FULL_STACK')->plainTextToken;

            return response()->json([
                'status_code' => 201,
                'message' => 'Le compte a été créé avec succès.',
                'success' => true,
                'error' => false,
                'data' => [
                    'token' => $token,
                    'user' => $user,
                ],
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer plus tard.',
                'success' => false,
                'error' => true,
                'exception' => $e->getMessage(),
            ], 500);
        }
    }
}
