<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

        if (!$result['success']) {
            if ($result['error'] === 'Invalid credentials') {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($result['error'] === 'E-mail not verified') {
                return response()->json([
                    'message' => 'You did not verified your E-mail yet!'
                ], Response::HTTP_FORBIDDEN);
            }
        }

        return response()->json([
            'message' => 'You logged successfully',
            'token' => $result['token'],
            'user' => $result['user']
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $user = $this->authService->storeUser($credentials);

        return response()->json([
            'message' => 'User has been registered. Verify your E-mail to get your account activated.',
            'user' => $user
        ], Response::HTTP_CREATED);
    }

    public function verifyEmail(Request $request): JsonResponse
    {
        $result = $this->authService->verifyEmail($request->id);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message']
        ], $result['status']);
    }

    public function resendVerification(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $result = $this->authService->resendVerificationEmail($request->email);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message']
        ], $result['status']);
    }

    public function logout(Request $request):JsonResponse
    {
        $user = $this->authService->logoutUser($request->user());

        return response()->json([
            'message' => $user['message']
        ], $user['status']);
    }
}
