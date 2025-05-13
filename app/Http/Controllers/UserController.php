<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Services\UserService;

class UserController
{
    public function __construct(protected UserService $userService)
    {}

    public function index()
    {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        try {
            $this->userService->storeUser($validated);

            return response()->json([
                'message' => 'User created successfully'
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = $this->userService->showUser($id);

            return $user ? response()->json([
                'message' => 'User found',
                'data' => $user
            ]) : response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|min:3',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8'
        ]);

        try {
            $user = $this->userService->updateUser($id, $validated);

            return $user ? response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ], Response::HTTP_OK) : response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->userService->destroyUser($id);

            return response()->json([
                'message' => 'Usuário deletado com sucesso'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao deletar usuário',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
