<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Services\AuthService;
class AuthController
{
    public function __construct(protected AuthService $authService) {}

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $result = $this->authService->userLogin($credentials);

        if (!$result) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $result['token'],
            'user' => $result['user']
        ]);
    }
}
