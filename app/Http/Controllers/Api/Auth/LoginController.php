<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login a user
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse{
        try {

            if(Auth::attempt($request->only('email', 'password'))){

                return response()->json([
                    'status_code' => 200,
                    'message' => 'Authentification réussite',
                    'success' => true,
                    'error' => false,
                    'data' => [
                        'user' => Auth::user(),
                        'token' => Auth::user()->createToken('NAJ_PRO_FREELANCE_FULL_STACK')->plainTextToken,
                    ]
                ], 200);
            }else{
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Echec de l\'authentification',
                    'success' => false,
                    'error' => true,
                ], 401);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Oups... Quelque chose s\'est mal passé',
                'success' => false,
                'error' => true,
            ]);
        }
    }
}
