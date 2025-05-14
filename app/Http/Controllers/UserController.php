<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\UserService;

class UserController
{
    public function __construct(protected UserService $userService)
    {}


    public function show(string $id): JsonResponse
    {
        $user = $this->userService->showUser($id);

        return response()->json([
            'data' => $user['data'],
            'message' => $user['message']
        ], $user['status']);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|min:3',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8'
        ]);

        $user = $this->userService->updateUser($id, $validated);

        return response()->json([
            'message' => $user['message'],
            'data' => $user['data']
        ], $user['status']);

    }

    public function destroy(string $id): JsonResponse
    {
        $user = $this->userService->destroyUser($id);

        return response()->json([
            'message' => $user['message']
        ], $user['status']);
    }
}
