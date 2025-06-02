<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Services\ProductService;

class ProductController
{
    public function __construct(protected ProductService $productService)
    {}

    public function index(string $userId): JsonResponse
    {
        $result = $this->productService->listUserProducts($userId);

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], $result['status']);
    }

    public function store(Request $request, string $userId): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $result = $this->productService->createUserProduct($userId, $validated);

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], $result['status']);
    }

    public function show(string $userId, string $productId): JsonResponse
    {
        $result = $this->productService->showUserProduct($userId, $productId);

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], $result['status']);
    }

    public function update(Request $request, string $userId, string $productId): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|min:3',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = asset('storage/' . $path);
        }

        $result = $this->productService->updateUserProduct($userId, $productId, $validated);

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], $result['status']);
    }

    public function destroy(string $userId, string $productId): JsonResponse
    {
        $result = $this->productService->destroyUserProduct($userId, $productId);

        return response()->json([
            'message' => $result['message'] ?? null
        ], $result['status']);
    }
}
